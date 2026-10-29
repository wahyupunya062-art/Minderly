<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Kategori;
use App\Services\PrioritasService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        // Statistik seluruh tugas, tidak terpengaruh pencarian atau filter.
        $totalTugas = Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        )->count();

       $tugasAktif = Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        )->whereIn('status', [
            'Belum Mulai',
            'Sedang Dikerjakan',
        ])->count();

      $tugasSelesai = Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        )->where('status', 'Selesai')->count();

        // Prioritas tinggi: skor >= 80 atau deadline terlewat,
        // selama tugas belum selesai.
                    $prioritasTinggi = Tugas::where(
                'id_pengguna',
                auth()->user()->id_pengguna
            )
                ->where('status', '!=', 'Selesai')
                ->where(function ($q) {
                    $q->where('skor_prioritas', '>=', 80)
                        ->orWhere(function ($sub) {
                            $sub->whereNotNull('deadline')
                                ->whereDate('deadline', '<', today());
                        });
                })
    ->count();

        // Query daftar tugas.
        $query = Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        );

        
      
        // PENCARIAN TUGAS
        $search = trim($request->input('search', ''));

        if ($search !== '') {
            $kata = strtolower($search);

            $query->where(function ($q) use ($search, $kata) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('penanggung_jawab', 'like', '%' . $search . '%')
                    ->orWhereIn('id_kategori', function ($sub) use ($search) {
                        $sub->select('id_kategori')
                            ->from('kategori')
                            ->where('nama_kategori', 'like', '%' . $search . '%');
                    });

                // Cari berdasarkan tingkat prioritas.
                if (in_array($kata, ['tinggi', 'prioritas tinggi'], true)) {
                    $q->orWhere(function ($p) {
                        $p->where('skor_prioritas', '>=', 80);
                    });
                } elseif (in_array($kata, ['sedang', 'prioritas sedang'], true)) {
                    $q->orWhere(function ($p) {
                        $p->where('skor_prioritas', '>=', 50)
                            ->where('skor_prioritas', '<', 80);
                    });
                } elseif (in_array($kata, ['rendah', 'prioritas rendah'], true)) {
                    $q->orWhere('skor_prioritas', '<', 50);
                }

                // Cari deadline dengan format tanggal.
                foreach (['Y-m-d', 'd-m-Y', 'd/m/Y'] as $format) {
                    try {
                        $tanggal = Carbon::createFromFormat(
                            '!' . $format,
                            $search
                        );

                        if (
                            $tanggal &&
                            $tanggal->format($format) === $search
                        ) {
                            $q->orWhereDate(
                                'deadline',
                                $tanggal->toDateString()
                            );
                            break;
                        }
                    } catch (\Throwable $e) {
                        // Abaikan jika format bukan tanggal yang valid.
                    }
                }
            });
        }

        
        // ==================================================
        // FILTER TUGAS
        // ==================================================
        $status = $request->input('status');
        $kataCari = mb_strtolower(trim($search));

        if (in_array($status, ['selesai', 'Selesai'], true)) {
            // Filter khusus tugas selesai.
            $query->where('status', 'Selesai');

        } elseif ($status === 'aktif') {
            // Tugas yang belum selesai.
            $query->whereIn('status', [
                'Belum Mulai',
                'Sedang Dikerjakan',
            ]);

        } elseif (
            $request->input('terlambat') == '1' ||
            $status === 'terlambat'
        ) {
            // Tugas terlambat yang belum selesai.
            $query->where('status', '!=', 'Selesai')
                ->whereNotNull('deadline')
                ->whereDate('deadline', '<', today());

        } elseif ($kataCari === 'selesai') {
            // Jika kata yang dicari adalah Selesai,
            // tampilkan tugas yang berstatus selesai saja.
            $query->where('status', 'Selesai');

        } else {
            // Pencarian lainnya hanya menampilkan
            // tugas yang belum selesai.
            $query->where('status', '!=', 'Selesai');
        }

        // ==================================================
        // PENGURUTAN TUGAS
        // ==================================================
        $sort = $request->input('sort', 'prioritas');

        if ($sort === 'deadline') {
            $query->orderBy('deadline', 'asc');

        } elseif ($sort === 'terbaru') {
            $query->orderBy('dibuat_pada', 'desc');

        } elseif ($sort === 'terlama') {
            $query->orderBy('dibuat_pada', 'asc');

        } else {
            // Prioritaskan tugas terlambat yang belum selesai.
            $query->orderByRaw(
                "CASE
                    WHEN deadline IS NOT NULL
                    AND deadline < ?
                    AND status != 'Selesai'
                    THEN 1
                    ELSE 0
                END DESC",
                [today()->toDateString()]
            );

            // Berikutnya, skor prioritas tertinggi.
            $query->orderBy('skor_prioritas', 'desc');
        }

        // Ambil hasil daftar tugas.
        $tugas = $query->get();

        // Ambil kategori untuk kebutuhan tampilan.
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('tugas.index', compact(
            'tugas',
            'kategori',
            'totalTugas',
            'tugasAktif',
            'tugasSelesai',
            'prioritasTinggi'
        ));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('tugas.tambah', compact('kategori'));
    }

    public function store(Request $request, PrioritasService $prioritas)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'tingkat_kesulitan' => 'required|integer|min:1|max:5',
            'dampak' => 'required|integer|min:1|max:10',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'penanggung_jawab' => 'nullable|string|max:100',
        ]);

        $kategori = Kategori::findOrFail($request->id_kategori);

        // Penanggung jawab hanya untuk tugas kelompok.
        $penanggungJawab = null;

        if ($kategori->nama_kategori === 'Kelompok') {
            $penanggungJawab = $request->penanggung_jawab;
        }

        $score = $prioritas->hitung(
            $request->deadline,
            $request->tingkat_kesulitan,
            $request->dampak
        );

        Tugas::create([
            'id_pengguna' => auth()->user()->id_pengguna,
            'id_kategori' => $request->id_kategori,
            'penanggung_jawab' => $penanggungJawab,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'dampak' => $request->dampak,
            'skor_prioritas' => $score,
            'status' => 'Belum Mulai',
        ]);

        return redirect('/tugas')
            ->with('success', 'Tugas berhasil ditambahkan');
    }

    public function edit($id)
    {
                $tugas = Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        )->findOrFail($id);
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('tugas.edit', compact('tugas', 'kategori'));
    }

    public function update(
        Request $request,
        $id,
        PrioritasService $prioritas
    ) {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'deadline' => 'required|date',
            'tingkat_kesulitan' => 'required|integer|min:1|max:5',
            'dampak' => 'required|integer|min:1|max:10',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'penanggung_jawab' => 'nullable|string|max:100',
            'status' => 'required|in:Belum Mulai,Sedang Dikerjakan,Selesai',
        ]);

       $tugas = Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        )->findOrFail($id);
        $kategori = Kategori::findOrFail($request->id_kategori);

        // Penanggung jawab hanya disimpan untuk kategori Kelompok.
        $penanggungJawab = null;

        if ($kategori->nama_kategori === 'Kelompok') {
            $penanggungJawab = $request->penanggung_jawab;
        }

        $score = $prioritas->hitung(
            $request->deadline,
            $request->tingkat_kesulitan,
            $request->dampak
        );

        $tugas->update([
            'id_kategori' => $request->id_kategori,
            'penanggung_jawab' => $penanggungJawab,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'dampak' => $request->dampak,
            'skor_prioritas' => $score,
            'status' => $request->status,
        ]);

        return redirect('/tugas')
            ->with('success', 'Tugas berhasil diperbarui');
    }

    public function destroy($id)
    {
        Tugas::where(
            'id_pengguna',
            auth()->user()->id_pengguna
        )->findOrFail($id)->delete();

        return redirect('/tugas')
            ->with('success', 'Tugas berhasil dihapus');
    }
}
