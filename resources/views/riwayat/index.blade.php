
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header --}}
        <div class="mb-6">
            <p class="mb-2 text-sm text-slate-500">
                Dashboard / Riwayat Tugas
            </p>

            <h1 class="text-2xl font-bold text-slate-800 md:text-3xl">
                Riwayat Tugas
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Lihat dan telusuri tugas yang pernah kamu buat di Minderly.
            </p>
        </div>

        {{-- Pencarian dan filter --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <form action="{{ route('riwayat.index') }}"
                  method="GET"
                  class="flex flex-col gap-3 md:flex-row">

                <input
                    type="text"
                    name="cari"
                    value="{{ request('cari') }}"
                    placeholder="Cari judul atau deskripsi tugas..."
                    class="min-w-0 flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                <select
                    name="status"
                    class="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500"
                >
                    <option value="">Semua status</option>

                    <option value="Belum Mulai"
                        @selected(request('status') === 'Belum Mulai')>
                        Belum Mulai
                    </option>

                    <option value="Sedang Dikerjakan"
                        @selected(request('status') === 'Sedang Dikerjakan')>
                        Sedang Dikerjakan
                    </option>

                    <option value="Selesai"
                        @selected(request('status') === 'Selesai')>
                        Selesai
                    </option>
                </select>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Cari
                </button>

                @if(request()->filled('cari') || request()->filled('status'))
                    <a
                        href="{{ route('riwayat.index') }}"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50"
                    >
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Daftar riwayat --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800">
                    Daftar Riwayat
                </h2>
            </div>

            @forelse($tugas as $item)
                <div class="border-b border-slate-100 p-5 last:border-b-0 transition hover:bg-slate-50">
                    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-start">

                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-slate-800">
                                {{ $item->judul }}
                            </h3>

                            <p class="mt-2 whitespace-pre-line text-sm text-slate-500">
                                {{ $item->deskripsi ?: 'Tidak ada deskripsi.' }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-500">
                                <span>
                                    Dibuat:
                                    {{ \Carbon\Carbon::parse($item->dibuat_pada)->format('d M Y') }}
                                </span>

                                <span>
                                    Tenggat:
                                    {{ $item->deadline
                                        ? \Carbon\Carbon::parse($item->deadline)->format('d M Y')
                                        : 'Belum ditentukan' }}
                                </span>

                                <span>
                                    Prioritas:
                                    {{ $item->skor_prioritas ?? 'Belum ditentukan' }}
                                </span>
                            </div>
                        </div>

                        <div class="shrink-0">
                            @if($item->status === 'Selesai')
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                    Selesai
                                </span>
                            @elseif($item->status === 'Sedang Dikerjakan')
                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700">
                                    Sedang Dikerjakan
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                    Belum Mulai
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="px-5 py-14 text-center">
                    <div class="mb-3 flex justify-center">
                        <svg class="h-12 w-12 text-slate-300"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h6"/>
                        </svg>
                    </div>

                    <h3 class="font-semibold text-slate-700">
                        Belum ada riwayat tugas
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Tugas yang kamu buat akan ditampilkan di halaman ini.
                    </p>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        <div class="mt-5">
            {{ $tugas->links() }}
        </div>

    </div>
</div>
@endsection
