<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KalenderController extends Controller
{
    public function index()
    {
        $pengguna = auth()->user();

        abort_unless(
            $pengguna instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        $agendas = Agenda::where(
            'id_pengguna',
            $pengguna->id_pengguna
        )
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->get();

        return view('kalender.index', compact('agendas'));
    }

    public function store(Request $request)
    {
        $pengguna = auth()->user();

        abort_unless(
            $pengguna instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        $validated = $request->validate([
            'nama_agenda' => ['required', 'string', 'max:150'],
            'jenis' => ['required', Rule::in(['rutin', 'tambahan'])],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'hari_rutin' => [
                Rule::requiredIf($request->jenis === 'rutin'),
                'nullable',
                'array',
            ],
            'hari_rutin.*' => [
                Rule::in([
                    'Senin', 'Selasa', 'Rabu', 'Kamis',
                    'Jumat', 'Sabtu', 'Minggu',
                ]),
            ],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ], [
            'nama_agenda.required' => 'Nama agenda wajib diisi.',
            'hari_rutin.required' => 'Pilih minimal satu hari rutin.',
            'hari_rutin.*.in' => 'Pilihan hari tidak valid.',
            'tanggal_selesai.after_or_equal' =>
                'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'jam_selesai.after' =>
                'Jam selesai harus lebih lambat daripada jam mulai.',
        ]);

        if ($validated['jenis'] === 'rutin') {
            if (empty($validated['hari_rutin'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'hari_rutin' => 'Pilih minimal satu hari rutin.',
                    ]);
            }
        } else {
            // Agenda tambahan hanya terjadi satu kali.
            $validated['tanggal_selesai'] = null;
            $validated['hari_rutin'] = null;
        }

        $validated['id_pengguna'] = $pengguna->id_pengguna;

        Agenda::create($validated);

        return redirect()
            ->route('kalender.index')
            ->with('success', 'Agenda berhasil disimpan.');
    }

    public function destroy(Agenda $agenda)
    {
        $pengguna = auth()->user();

        abort_unless(
            $pengguna instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        abort_unless(
            (int) $agenda->id_pengguna ===
            (int) $pengguna->id_pengguna,
            403,
            'Kamu tidak memiliki akses ke agenda ini.'
        );

        $agenda->delete();

        return redirect()
            ->route('kalender.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}
