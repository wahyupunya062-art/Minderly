<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Team;
use App\Models\TeamMessage;
use Illuminate\Http\Request;

class TeamChatController extends Controller
{
    /**
     * Menampilkan halaman chat tim.
     */
    public function index(Team $team)
    {
        $pengguna = auth()->user();

        abort_unless(
            $pengguna instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        $bolehAkses = (int) $team->owner_id === (int) $pengguna->id_pengguna
            || $team->members()
                ->where('user_id', $pengguna->id_pengguna)
                ->exists();

        abort_unless(
            $bolehAkses,
            403,
            'Kamu tidak memiliki akses ke chat tim ini.'
        );

        $messages = TeamMessage::with('pengguna')
            ->where('team_id', $team->id)
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return view('teams.chat', compact('team', 'messages'));
    }

    /**
     * Menyimpan pesan baru.
     */
    public function store(Request $request, Team $team)
    {
        $pengguna = auth()->user();

        abort_unless(
            $pengguna instanceof Pengguna,
            403,
            'Silakan login menggunakan akun Minderly.'
        );

        $bolehAkses = (int) $team->owner_id === (int) $pengguna->id_pengguna
            || $team->members()
                ->where('user_id', $pengguna->id_pengguna)
                ->exists();

        abort_unless(
            $bolehAkses,
            403,
            'Kamu tidak memiliki akses ke chat tim ini.'
        );

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max' => 'Pesan maksimal 5000 karakter.',
        ]);

        TeamMessage::create([
            'team_id' => $team->id,
            'user_id' => $pengguna->id_pengguna,
            'message' => $validated['message'],
        ]);

        return redirect()
            ->route('teams.chat.index', $team->id)
            ->with('success', 'Pesan berhasil dikirim.');
    }
}