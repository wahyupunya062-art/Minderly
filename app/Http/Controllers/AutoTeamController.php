<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AutoTeamController extends Controller
{
    public function assign(Request $request)
    {
        $penggunaLogin = auth()->user();

        // Pastikan hanya admin yang dapat membagi tim.
        abort_unless(
            $penggunaLogin instanceof Pengguna
                && $penggunaLogin->role === 'admin',
            403,
            'Hanya admin yang dapat membagi tim otomatis.'
        );

        $validator = Validator::make($request->all(), [
            'id_proyek' => 'required|integer|exists:proyek,id_proyek',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $idProyek = (int) $request->id_proyek;

        $kolomKuis = [
            'riset',
            'menulis',
            'desain',
            'presentasi',
            'analisis',
            'perencanaan',
            'ketelitian',
            'kolaborasi',
            'disiplin_deadline',
            'mandiri',
            'waktu_tersedia',
            'beban_kerja',
        ];

        try {
            $hasil = DB::transaction(function () use (
                $idProyek,
                $kolomKuis
            ) {
                // Kunci baris proyek selama transaksi untuk mengurangi
                // risiko pembagian proyek yang sama secara bersamaan.
                $proyek = DB::table('proyek')
                    ->where('id_proyek', $idProyek)
                    ->lockForUpdate()
                    ->first();

                if (!$proyek) {
                    return [
                        'status' => 'tidak_ada',
                    ];
                }

                // Jangan membuat tim baru jika proyek sudah memiliki tim.
                $sudahAdaTim = DB::table('project_teams')
                    ->where('id_proyek', $idProyek)
                    ->exists();

                if ($sudahAdaTim) {
                    return [
                        'status' => 'duplikat',
                    ];
                }

                // Ambil pengguna yang semua nilai kuisnya lengkap,
                // yaitu berada pada rentang 1 sampai 5.
                $peserta = DB::table('profil_pengguna')
                    ->join(
                        'pengguna',
                        'profil_pengguna.id_pengguna',
                        '=',
                        'pengguna.id_pengguna'
                    )
                    ->select(
                        'profil_pengguna.id_pengguna',
                        ...array_map(
                            fn ($kolom) => 'profil_pengguna.' . $kolom,
                            $kolomKuis
                        )
                    )
                    ->where(function ($query) use ($kolomKuis) {
                        foreach ($kolomKuis as $kolom) {
                            $query->whereBetween(
                                'profil_pengguna.' . $kolom,
                                [1, 5]
                            );
                        }
                    })
                    ->get()
                    ->unique('id_pengguna')
                    ->values();

                if ($peserta->isEmpty()) {
                    return [
                        'status' => 'peserta_kosong',
                    ];
                }

                // Urutkan peserta berdasarkan skor kepemimpinan.
                $peserta = $peserta->sortByDesc(
                    fn ($p) => (int) $p->perencanaan
                        + (int) $p->kolaborasi
                )->values();

                // Maksimal lima orang dalam setiap tim.
                $kelompok = $peserta->chunk(5);
                $nomorTim = 1;

                foreach ($kelompok as $anggota) {
                    // Pilih ketua dengan skor perencanaan + kolaborasi
                    // tertinggi dalam tim tersebut.
                    $ketua = $anggota->sortByDesc(
                        fn ($p) => (int) $p->perencanaan
                            + (int) $p->kolaborasi
                    )->first();

                    $namaTim = mb_substr(
                        $proyek->nama_proyek . ' - Tim ' . $nomorTim,
                        0,
                        100
                    );

                    $tim = Team::create([
                        'name' => $namaTim,
                        'description' => 'Tim hasil pembagian otomatis.',
                        'owner_id' => $ketua->id_pengguna,
                    ]);

                    // Hubungkan tim dengan proyek.
                    DB::table('project_teams')->insert([
                        'id_proyek' => $idProyek,
                        'team_id' => $tim->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($anggota as $pesertaTim) {
                        $idAnggota = (int) $pesertaTim->id_pengguna;
                        $idKetua = (int) $ketua->id_pengguna;
                        $adalahKetua = $idAnggota === $idKetua;

                        // Simpan anggota agar dapat mengakses tim dan chat.
                        $tim->members()->firstOrCreate(
                            ['user_id' => $idAnggota],
                            [
                                'role' => $adalahKetua
                                    ? 'leader'
                                    : 'member',
                            ]
                        );

                        // Simpan keanggotaan proyek tanpa duplikasi.
                        $sudahTerdaftar = DB::table('anggota_proyek')
                            ->where('id_proyek', $idProyek)
                            ->where('id_pengguna', $idAnggota)
                            ->exists();

                        if (!$sudahTerdaftar) {
                            DB::table('anggota_proyek')->insert([
                                'id_proyek' => $idProyek,
                                'id_pengguna' => $idAnggota,
                                'peran' => $adalahKetua
                                    ? 'Ketua'
                                    : 'Anggota',
                            ]);
                        }
                    }

                    $nomorTim++;
                }

                return [
                    'status' => 'berhasil',
                    'jumlah_tim' => $kelompok->count(),
                    'jumlah_peserta' => $peserta->count(),
                ];
            });

            if ($hasil['status'] === 'duplikat') {
                return back()->with(
                    'error',
                    'Proyek ini sudah memiliki tim. Pembagian ulang dibatalkan agar tidak terjadi duplikasi.'
                );
            }

            if ($hasil['status'] === 'peserta_kosong') {
                return back()->with(
                    'error',
                    'Belum ada pengguna dengan profil kuis lengkap.'
                );
            }

            if ($hasil['status'] === 'tidak_ada') {
                return back()->with(
                    'error',
                    'Proyek tidak ditemukan.'
                );
            }

            return back()->with(
                'success',
                'Pembagian tim berhasil. '
                    . $hasil['jumlah_peserta'] . ' peserta dibagi menjadi '
                    . $hasil['jumlah_tim'] . ' tim.'
            );
        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Pembagian tim gagal. Periksa log Laravel untuk detailnya.'
            );
        }
    }
}