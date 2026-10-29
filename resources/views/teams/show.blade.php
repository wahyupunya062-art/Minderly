@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header Tim --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('teams.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Kolaborasi Tim
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                {{ $team->name }}
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                {{ $team->description ?: 'Belum ada deskripsi tim.' }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex w-fit rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700">
                {{ $team->members->count() }} anggota terdaftar
            </span>

            {{-- Tombol Chat Tim --}}
            <a href="{{ route('teams.chat.index', $team->id) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                <span>💬</span>
                <span>Chat Tim</span>
            </a>
        </div>
    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan kesalahan --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Informasi Tim --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800">
            Informasi Tim
        </h2>

        <div class="mt-4 space-y-3 text-sm">
            <div>
                <p class="text-gray-500">Nama tim</p>
                <p class="font-semibold text-gray-800">{{ $team->name }}</p>
            </div>

            <div>
                <p class="text-gray-500">Deskripsi</p>
                <p class="text-gray-700">
                    {{ $team->description ?: 'Belum ada deskripsi.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Undangan Anggota: khusus pemilik tim --}}
    @if ((int) $team->owner_id === (int) auth()->user()->id_pengguna)
        <div class="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800">
                Undang Anggota Tim
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Buat tautan undangan agar pengguna dapat bergabung ke tim ini.
            </p>

            <form action="{{ route('teams.invite.generate', $team->id) }}"
                  method="POST"
                  class="mt-4">
                @csrf

                <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">
                    Buat Tautan Undangan
                </button>
            </form>

            {{-- Tampilkan tautan undangan --}}
            @if (session('invite_url'))
                <div class="mt-5 space-y-3">
                    <label for="inviteLink"
                           class="block text-sm font-medium text-gray-700">
                        Tautan Undangan
                    </label>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input
                            type="text"
                            id="inviteLink"
                            value="{{ session('invite_url') }}"
                            readonly
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
                        >

                        <button
                            type="button"
                            onclick="copyInviteLink()"
                            class="rounded-xl border border-blue-600 px-5 py-3 font-semibold text-blue-600 hover:bg-blue-50">
                            Salin Tautan
                        </button>
                    </div>

                    <a
                        href="https://wa.me/?text={{ urlencode('Yuk, bergabung dengan tim ' . $team->name . ' di Minderly! ' . session('invite_url')) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex rounded-xl bg-green-600 px-5 py-3 font-semibold text-white transition hover:bg-green-700">
                        Bagikan ke WhatsApp
                    </a>

                    <p class="text-xs text-gray-500">
                        Tautan menggunakan alamat aplikasi saat ini.
                        Untuk digunakan orang lain, Minderly harus dapat diakses
                        melalui jaringan atau alamat publik.
                    </p>
                </div>
            @endif
        </div>

        {{-- Form Tambah Anggota --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800">
                Tambah Anggota
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Masukkan email pengguna yang sudah terdaftar di Minderly.
            </p>

            <form action="{{ route('teams.members.store', $team->id) }}"
                  method="POST"
                  class="mt-5 flex flex-col gap-3 sm:flex-row">
                @csrf

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="contoh@email.com"
                    required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                >

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">
                    + Tambah Anggota
                </button>
            </form>
        </div>
    @endif

    {{-- Daftar Anggota --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800">
                Anggota Tim
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Daftar pengguna yang sudah bergabung dengan tim ini.
            </p>
        </div>

        <div class="divide-y divide-gray-100">
            {{-- Pemilik Tim --}}
            @php
                $pemilik = $team->owner;
            @endphp

            @if ($pemilik)
                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $pemilik->nama ?? $pemilik->name ?? $pemilik->email ?? 'Pemilik Tim' }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $pemilik->email ?? '-' }}
                        </p>
                    </div>

                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                        Pemilik
                    </span>
                </div>
            @endif

            {{-- Anggota Tim --}}
            @forelse ($team->members as $member)
                <div class="flex items-center justify-between gap-4 p-5">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $member->pengguna?->nama ?? $member->pengguna?->name ?? 'Pengguna tidak ditemukan' }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $member->pengguna?->email ?? '-' }}
                        </p>
                    </div>

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                        {{ ucfirst($member->role ?? 'member') }}
                    </span>
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="font-semibold text-gray-700">
                        Belum ada anggota lain.
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Bagikan tautan undangan agar pengguna lain dapat bergabung.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Fungsi Salin Tautan --}}
<script>
    function copyInviteLink() {
        const input = document.getElementById('inviteLink');

        if (!input) {
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value)
                .then(() => alert('Tautan undangan berhasil disalin!'))
                .catch(() => fallbackCopy(input));
        } else {
            fallbackCopy(input);
        }
    }

    function fallbackCopy(input) {
        input.focus();
        input.select();
        input.setSelectionRange(0, 99999);

        const berhasil = document.execCommand('copy');

        if (berhasil) {
            alert('Tautan undangan berhasil disalin!');
        } else {
            alert('Gagal menyalin. Silakan salin tautan secara manual.');
        }
    }
</script>
@endsection