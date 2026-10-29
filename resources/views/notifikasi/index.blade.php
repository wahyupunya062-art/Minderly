
@extends('layouts.app')

@section('header', 'Notifikasi')

@section('content')
<div class="space-y-6">

    {{-- Header halaman --}}
    <div class="bg-gradient-to-r from-[#064B87] to-blue-500 rounded-2xl p-6 md:p-8 text-white">
        <p class="text-blue-100 text-sm mb-2">Pusat Informasi Minderly</p>

        <h2 class="text-2xl md:text-3xl font-bold">
            Notifikasi Tugas
        </h2>

        <p class="text-blue-100 mt-2">
            Pantau deadline dan tugas prioritasmu agar tidak terlewat.
        </p>
    </div>

    {{-- Ringkasan notifikasi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Total notifikasi --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500">
                Total Notifikasi
            </p>

            <p class="text-3xl font-bold text-[#064B87] mt-2">
                {{ $jumlahNotifikasi }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Tugas yang perlu diperhatikan
            </p>
        </div>

        {{-- Deadline terdekat --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500">
                Deadline Terdekat
            </p>

            <p class="text-3xl font-bold text-orange-500 mt-2">
                {{ $notifikasi->filter(function ($item) use ($hariIni) {
                    return $item->deadline
                        && \Carbon\Carbon::parse($item->deadline)->gte($hariIni);
                })->count() }}
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Tugas dengan deadline hari ini atau mendatang
            </p>
        </div>

    </div>

    {{-- Daftar notifikasi --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="p-5 md:p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-gray-800">
                    Semua Notifikasi
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Deadline terdekat dan tugas dengan prioritas tinggi
                </p>
            </div>

            <span class="text-sm text-gray-500 whitespace-nowrap">
                {{ $jumlahNotifikasi }} tugas
            </span>
        </div>

        <div class="divide-y divide-gray-100">

            @forelse ($notifikasi as $item)

                @php
                    $deadline = $item->deadline
                        ? \Carbon\Carbon::parse($item->deadline)
                        : null;

                    if ($deadline && $deadline->lt($hariIni)) {
                        $label = 'Deadline Terlewat';
                        $warna = 'bg-red-100 text-red-700';
                        $deskripsi = 'Tugas ini sudah melewati deadline.';
                    } elseif ($deadline && $deadline->isToday()) {
                        $label = 'Deadline Hari Ini';
                        $warna = 'bg-orange-100 text-orange-700';
                        $deskripsi = 'Tugas ini perlu diselesaikan hari ini.';
                    } elseif ($deadline && $deadline->lte($hariIni->copy()->addDays(3))) {
                        $label = 'Deadline Mendatang';
                        $warna = 'bg-blue-100 text-blue-700';
                        $deskripsi = 'Deadline tugas semakin dekat.';
                    } else {
                        $label = 'Prioritas Tinggi';
                        $warna = 'bg-purple-100 text-purple-700';
                        $deskripsi = 'Tugas ini memiliki skor prioritas tinggi.';
                    }
                @endphp

                <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50 transition">

                    {{-- Ikon --}}
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#064B87] flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>

                    {{-- Informasi tugas --}}
                    <div class="flex-1 min-w-0">

                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-semibold text-gray-800">
                                {{ $item->judul }}
                            </h4>

                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $warna }}">
                                {{ $label }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $deskripsi }}
                        </p>

                        <p class="text-xs text-gray-400 mt-2">
                            @if ($deadline)
                                Deadline: {{ $deadline->format('d M Y') }}
                            @else
                                Deadline belum ditentukan
                            @endif

                            @if ($item->skor_prioritas !== null)
                                · Skor prioritas: {{ $item->skor_prioritas }}
                            @endif
                        </p>

                    </div>

                    {{-- Tombol lihat tugas --}}
                    <a
                        href="{{ url('/tugas/edit/' . $item->id_tugas) }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#064B87] text-white text-sm font-medium hover:bg-blue-800 transition"
                    >
                        Lihat Tugas
                    </a>

                </div>

            @empty

                {{-- Kondisi jika tidak ada notifikasi --}}
                <div class="px-6 py-14 text-center">

                    <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 17h5l-1.5-2V11a6.5 6.5 0 10-13 0v4L4 17h5m1 4h4"
                            />
                        </svg>
                    </div>

                    <h4 class="font-semibold text-gray-800">
                        Belum Ada Notifikasi
                    </h4>

                    <p class="text-sm text-gray-500 mt-2">
                        Belum ada tugas yang memenuhi kriteria notifikasi.
                    </p>

                    <a
                        href="{{ url('/tugas') }}"
                        class="inline-block mt-5 px-5 py-2.5 rounded-xl bg-[#064B87] text-white text-sm font-medium hover:bg-blue-800 transition"
                    >
                        Lihat Manajemen Tugas
                    </a>

                </div>

            @endforelse

        </div>
    </div>

</div>
@endsection
