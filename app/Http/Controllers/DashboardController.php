<?php

namespace App\Http\Controllers;

use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = today();
        $batasNotifikasi = $hariIni->copy()->addDays(3);

        // ID pengguna yang sedang login.
        $idPengguna = auth()->user()->id_pengguna;

        // Query dasar: hanya tugas milik pengguna ini.
        $tugasSaya = fn () => Tugas::where(
            'id_pengguna',
            $idPengguna
        );

        // ==========================================
        // STATISTIK UTAMA
        // ==========================================

        $totalTugas = $tugasSaya()->count();

        $belumMulai = $tugasSaya()
            ->where('status', 'Belum Mulai')
            ->count();

        $dalamProses = $tugasSaya()
            ->where('status', 'Sedang Dikerjakan')
            ->count();

        $selesai = $tugasSaya()
            ->where('status', 'Selesai')
            ->count();

        $tugasAktifCount = $tugasSaya()
            ->whereIn('status', [
                'Belum Mulai',
                'Sedang Dikerjakan',
            ])
            ->count();

        $lewatTenggat = $tugasSaya()
            ->where('status', '!=', 'Selesai')
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', $hariIni)
            ->count();

        $prioritasTinggi = $tugasSaya()
            ->where('status', '!=', 'Selesai')
            ->where(function ($query) use ($hariIni) {
                $query->where('skor_prioritas', '>=', 80)
                    ->orWhere(function ($sub) use ($hariIni) {
                        $sub->whereNotNull('deadline')
                            ->whereDate('deadline', '<', $hariIni);
                    });
            })
            ->count();

        // ==========================================
        // DISTRIBUSI PRIORITAS
        // ==========================================

        $jumlahPrioritasTinggi = $tugasSaya()
            ->whereNotNull('skor_prioritas')
            ->where('skor_prioritas', '>=', 80)
            ->count();

        $jumlahPrioritasSedang = $tugasSaya()
            ->where('skor_prioritas', '>=', 50)
            ->where('skor_prioritas', '<', 80)
            ->count();

        $jumlahPrioritasRendah = $tugasSaya()
            ->whereNotNull('skor_prioritas')
            ->where('skor_prioritas', '<', 50)
            ->count();

        $jumlahPrioritasBelumDihitung = $tugasSaya()
            ->whereNull('skor_prioritas')
            ->count();

        // ==========================================
        // DATA DIAGRAM
        // ==========================================

        $diagramStatus = [
            'labels' => [
                'Belum Mulai',
                'Sedang Dikerjakan',
                'Selesai',
            ],
            'data' => [
                $belumMulai,
                $dalamProses,
                $selesai,
            ],
        ];

        $diagramPrioritas = [
            'labels' => [
                'Tinggi',
                'Sedang',
                'Rendah',
                'Belum dihitung',
            ],
            'data' => [
                $jumlahPrioritasTinggi,
                $jumlahPrioritasSedang,
                $jumlahPrioritasRendah,
                $jumlahPrioritasBelumDihitung,
            ],
        ];

        // ==========================================
        // PENGINGAT DEADLINE 7 HARI KE DEPAN
        // ==========================================

        $reminders = $tugasSaya()
            ->whereIn('status', [
                'Belum Mulai',
                'Sedang Dikerjakan',
            ])
            ->whereNotNull('deadline')
            ->whereDate('deadline', '>=', $hariIni)
            ->whereDate('deadline', '<=', $hariIni->copy()->addDays(7))
            ->orderBy('deadline')
            ->orderByDesc('skor_prioritas')
            ->limit(5)
            ->get();

        // ==========================================
        // NOTIFIKASI
        // ==========================================

        $notifikasiQuery = $tugasSaya()
            ->whereIn('status', [
                'Belum Mulai',
                'Sedang Dikerjakan',
            ])
            ->where(function ($query) use (
                $hariIni,
                $batasNotifikasi
            ) {
                $query->where(function ($deadlineQuery) use (
                    $hariIni,
                    $batasNotifikasi
                ) {
                    $deadlineQuery->whereNotNull('deadline')
                        ->whereDate('deadline', '<=', $batasNotifikasi);
                })->orWhere('skor_prioritas', '>=', 80);
            });

        $jumlahNotifikasi = (clone $notifikasiQuery)->count();

        $notifikasi = $notifikasiQuery
            ->orderByRaw(
                'CASE
                    WHEN deadline IS NOT NULL AND deadline < ? THEN 3
                    WHEN deadline = ? THEN 2
                    WHEN deadline IS NOT NULL AND deadline <= ? THEN 1
                    ELSE 0
                END DESC',
                [
                    $hariIni->toDateString(),
                    $hariIni->toDateString(),
                    $batasNotifikasi->toDateString(),
                ]
            )
            ->orderByDesc('skor_prioritas')
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        // ==========================================
        // DAFTAR TUGAS AKTIF
        // ==========================================

        $tugasAktif = $tugasSaya()
            ->whereIn('status', [
                'Belum Mulai',
                'Sedang Dikerjakan',
            ])
            ->orderByRaw(
                'CASE
                    WHEN deadline IS NOT NULL
                    AND deadline < ?
                    THEN 1 ELSE 0
                END DESC',
                [$hariIni->toDateString()]
            )
            ->orderByDesc('skor_prioritas')
            ->orderBy('deadline')
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'totalTugas',
            'belumMulai',
            'dalamProses',
            'selesai',
            'tugasAktifCount',
            'lewatTenggat',
            'prioritasTinggi',
            'diagramStatus',
            'diagramPrioritas',
            'reminders',
            'notifikasi',
            'jumlahNotifikasi',
            'tugasAktif'
        ));
    }
}
