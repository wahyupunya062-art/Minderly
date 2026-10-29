@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Kolaborasi Tim
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola tim dan bekerja sama menyelesaikan tugas.
            </p>
        </div>



<a href="{{ route('teams.create') }}"
   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">
    + Buat Tim Baru
</a>


    </div>

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan validasi --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
            <p class="mb-2 font-semibold">
                Ada data yang perlu diperbaiki:
            </p>
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Tim</p>
            <p class="mt-2 text-3xl font-bold text-gray-800">
                {{ $totalTim }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Anggota Tim</p>
            <p class="mt-2 text-3xl font-bold text-gray-800">
                {{ $totalAnggota }}
            </p>
        </div>
    </div>

    {{-- Daftar tim --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800">
                Daftar Tim
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Daftar tim yang sudah tersimpan di sistem.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr class="text-sm text-gray-500">
                        <th class="px-6 py-4 font-semibold">No.</th>
                        <th class="px-6 py-4 font-semibold">Nama Tim</th>
                        <th class="px-6 py-4 font-semibold">Deskripsi</th>
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($teams as $team)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $loop->iteration }}
                            </td>


<td class="px-6 py-4">
    <a href="{{ route('teams.show', $team->id) }}"
       class="font-semibold text-blue-600 hover:text-blue-800">
        {{ $team->name }}
    </a>
</td>


                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $team->description ?: 'Belum ada deskripsi' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                                    {{ $team->members_count }} anggota
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center">
                                <p class="font-semibold text-gray-700">
                                    Belum ada tim
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Gunakan formulir di atas untuk membuat tim pertama.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection