<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengguna;

class RiwayatTugasController extends Controller
{
    public function index(Request $request)
    {
        $pengguna = auth()->user();

        abort_unless($pengguna instanceof Pengguna, 403);

        $query = DB::table('tugas')
            ->where('id_pengguna', $pengguna->id_pengguna);

        // Pencarian berdasarkan judul atau deskripsi
        if ($request->filled('cari')) {
            $kataKunci = $request->cari;

            $query->where(function ($q) use ($kataKunci) {
                $q->where('judul', 'like', "%{$kataKunci}%")
                  ->orWhere('deskripsi', 'like', "%{$kataKunci}%");
            });
        }

        // Filter status sesuai enum di database
        $statusValid = [
            'Belum Mulai',
            'Sedang Dikerjakan',
            'Selesai',
        ];

        if (in_array($request->status, $statusValid, true)) {
            $query->where('status', $request->status);
        }

        // Tugas terbaru ditampilkan lebih dahulu
        $tugas = $query
            ->orderByDesc('dibuat_pada')
            ->orderByDesc('id_tugas')
            ->paginate(10)
            ->withQueryString();

        return view('riwayat.index', compact('tugas'));
    }
}
