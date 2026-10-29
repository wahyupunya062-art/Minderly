<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    // Menampilkan daftar tim milik pengguna atau tim yang diikuti
    public function index()
    {
        $pengguna = auth()->user();

        if (!$pengguna instanceof Pengguna) {
            abort(403, 'Silakan login menggunakan akun Minderly.');
        }

        $teams = Team::withCount('members')
            ->where(function ($query) use ($pengguna) {
                $query->where('owner_id', $pengguna->id_pengguna)
                    ->orWhereHas('members', function ($memberQuery) use ($pengguna) {
                        $memberQuery->where(
                            'user_id',
                            $pengguna->id_pengguna
                        );
                    });
            })
            ->latest()
            ->get();

        $totalTim = $teams->count();
        $totalAnggota = $teams->sum('members_count');

        return view('teams.index', compact(
            'teams',
            'totalTim',
            'totalAnggota'
        ));
    }

    // Menampilkan detail tim
    public function show(Team $team)
    {
        $pengguna = auth()->user();

        abort_unless(
            $pengguna instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        $bolehAkses =
            (int) $team->owner_id === (int) $pengguna->id_pengguna
            || $team->members()
                ->where('user_id', $pengguna->id_pengguna)
                ->exists();

        abort_unless(
            $bolehAkses,
            403,
            'Kamu tidak memiliki akses ke tim ini.'
        );

        $team->load(['members.pengguna']);

        return view('teams.show', compact('team'));
    }

    // Menampilkan halaman buat tim
    public function create()
    {
        return view('teams.create');
    }

    // Menyimpan tim baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama tim wajib diisi.',
            'name.max' => 'Nama tim maksimal 100 karakter.',
        ]);

        $pengguna = auth()->user();

        if (!$pengguna instanceof Pengguna) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Silakan login menggunakan akun Minderly terlebih dahulu.',
                ]);
        }

        $validated['owner_id'] = $pengguna->id_pengguna;

        Team::create($validated);

        return redirect()
            ->route('teams.index')
            ->with('success', 'Tim berhasil dibuat.');
    }

    // Menambahkan anggota tim berdasarkan email
    public function addMember(Request $request, Team $team)
    {
        $penggunaLogin = auth()->user();

        // Pastikan pengguna sudah login
        abort_unless(
            $penggunaLogin instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        // Hanya pemilik tim yang boleh menambahkan anggota
        abort_unless(
            (int) $team->owner_id === (int) $penggunaLogin->id_pengguna,
            403,
            'Hanya pemilik tim yang dapat menambahkan anggota.'
        );

        // Validasi email
        $validated = $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email anggota wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Cari pengguna berdasarkan email
        $calonAnggota = Pengguna::where(
            'email',
            $validated['email']
        )->first();

        if (!$calonAnggota) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email tersebut belum terdaftar di Minderly.',
                ]);
        }

        // Pemilik tidak perlu ditambahkan sebagai anggota
        if (
            (int) $calonAnggota->id_pengguna
            === (int) $team->owner_id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Kamu adalah pemilik tim ini.',
                ]);
        }

        // Periksa apakah pengguna sudah menjadi anggota
        $sudahMenjadiAnggota = $team->members()
            ->where('user_id', $calonAnggota->id_pengguna)
            ->exists();

        if ($sudahMenjadiAnggota) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Pengguna tersebut sudah menjadi anggota tim.',
                ]);
        }

        // Simpan anggota baru ke database
        $team->members()->create([
            'user_id' => $calonAnggota->id_pengguna,
            'role' => 'member',
        ]);

        return redirect()
            ->route('teams.show', $team->id)
            ->with('success', 'Anggota berhasil ditambahkan ke tim.');
    }


public function generateInvite(Team $team)
{
    $pengguna = auth()->user();

    abort_unless(
        $pengguna instanceof Pengguna,
        403,
        'Silakan login menggunakan akun Minderly.'
    );

    abort_unless(
        (int) $team->owner_id === (int) $pengguna->id_pengguna,
        403,
        'Hanya pemilik tim yang dapat membuat tautan undangan.'
    );

    if (!$team->invite_token) {
        $team->invite_token = Str::random(40);
        $team->save();
    }

    $inviteUrl = url('/teams/invite/' . $team->invite_token);

    return redirect()
        ->route('teams.show', $team->id)
        ->with('invite_url', $inviteUrl)
        ->with('success', 'Tautan undangan berhasil dibuat.');
}

public function joinByInvite(string $token)
{
    $pengguna = auth()->user();

    if (!$pengguna instanceof Pengguna) {
        abort(403, 'Silakan login ke Minderly terlebih dahulu.');
    }

    $team = Team::where('invite_token', $token)->firstOrFail();

    // Pemilik tim tidak perlu bergabung sebagai anggota.
    if ((int) $team->owner_id === (int) $pengguna->id_pengguna) {
        return redirect()
            ->route('teams.show', $team->id)
            ->with('success', 'Kamu adalah pemilik tim ini.');
    }

    // Cegah pengguna bergabung dua kali.
    $sudahMenjadiAnggota = $team->members()
        ->where('user_id', $pengguna->id_pengguna)
        ->exists();

    if (!$sudahMenjadiAnggota) {
        $team->members()->create([
            'user_id' => $pengguna->id_pengguna,
            'role' => 'member',
        ]);
    }

    return redirect()
        ->route('teams.show', $team->id)
        ->with(
            'success',
            $sudahMenjadiAnggota
                ? 'Kamu sudah menjadi anggota tim ini.'
                : 'Berhasil bergabung ke tim ' . $team->name . '!'
        );
}

}