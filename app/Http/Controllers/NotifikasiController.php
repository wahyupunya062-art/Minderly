<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $pengguna = auth()->user();
        $hariIni = today();
        $batasDeadline = today()->addDays(3);

        // Ambil hanya tugas milik pengguna yang sedang login.
        $notifikasi = DB::table('tugas')
            ->where('id_pengguna', $pengguna->id_pengguna)
            ->where('status', '!=', 'Selesai')
            ->where(function ($query) use ($batasDeadline) {
                $query->where(function ($subQuery) use ($batasDeadline) {
                    $subQuery->whereNotNull('deadline')
                        ->whereDate('deadline', '<=', $batasDeadline);
                })
                ->orWhere('skor_prioritas', '>=', 80);
            })
            ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
            ->orderBy('deadline', 'asc')
            ->get();

        $jumlahNotifikasi = $notifikasi->count();

        return view('notifikasi.index', compact(
            'notifikasi',
            'jumlahNotifikasi',
            'hariIni'
        ));
    }
}
