@extends('layouts.app')

@section('title', 'Dashboard - Minderly')
@section('header', 'Dashboard')

@section('content')

@php
    $persentaseSelesai = $totalTugas > 0
        ? round(($selesai / $totalTugas) * 100)
        : 0;
@endphp

<div class="max-w-7xl mx-auto space-y-7">

    {{-- SAMBUTAN --}}
    <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#064B87] to-[#2474C6] px-6 py-7 sm:px-8 sm:py-9 text-white shadow-lg">
        <div class="pointer-events-none absolute -right-10 -top-16 w-56 h-56 rounded-full border-[25px] border-white/10"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
            <div>
                <p class="text-sm text-blue-100">
                    Ruang kerjamu, lebih tertata.
                </p>

                <h2 class="mt-2 text-2xl sm:text-3xl font-bold">
                    Halo, selamat datang!
                </h2>

                <p class="mt-2 text-sm sm:text-base text-blue-100 max-w-xl">
                    Pantau progres dan fokus pada hal yang paling penting hari ini.
                </p>
            </div>

            <a href="{{ url('/tugas') }}"
               class="inline-flex w-fit items-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-bold text-[#064B87] hover:bg-blue-50 transition">
                Lihat semua tugas
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </section>

    {{-- RINGKASAN --}}
    <section>
        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-800">
                Ringkasan Tugas
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Gambaran progres pekerjaanmu saat ini.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- TOTAL --}}
            <article class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Total Tugas</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $totalTugas }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Semua tugas tercatat
                </p>
            </article>

            {{-- DALAM PROSES --}}
            <article class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Dalam Proses</p>
                        <p class="text-3xl font-bold text-amber-600 mt-2">
                            {{ $dalamProses }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Sedang dikerjakan
                </p>
            </article>

            {{-- SELESAI --}}
            <article class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Tugas Selesai</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ $selesai }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-xl">
                        <i class="fas fa-check"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    {{ $persentaseSelesai }}% dari semua tugas
                </p>
            </article>

            {{-- TERLAMBAT --}}
            <article class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Lewat Tenggat</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">
                            {{ $lewatTenggat }}
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Perlu perhatianmu
                </p>
            </article>

        </div>
    </section>

    {{-- GRAFIK --}}
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <article class="bg-white border border-gray-100 rounded-xl p-5 sm:p-6 shadow-sm">
            <h2 class="font-bold text-gray-800">
                Status Tugas
            </h2>

            <p class="text-sm text-gray-500 mt-1 mb-5">
                Perbandingan progres seluruh tugas.
            </p>

            <div class="relative h-64">
                <canvas id="statusChart"
                        role="img"
                        aria-label="Diagram status tugas"></canvas>
            </div>
        </article>

        <article class="bg-white border border-gray-100 rounded-xl p-5 sm:p-6 shadow-sm">
            <h2 class="font-bold text-gray-800">
                Distribusi Prioritas
            </h2>

            <p class="text-sm text-gray-500 mt-1 mb-5">
                Jumlah tugas berdasarkan skor prioritas.
            </p>

            <div class="relative h-64">
                <canvas id="priorityChart"
                        role="img"
                        aria-label="Diagram distribusi prioritas"></canvas>
            </div>
        </article>

    </section>

    {{-- TUGAS AKTIF DAN DEADLINE --}}
    <section class="grid grid-cols-1 gap-5">

        {{-- DEADLINE MENDATANG --}}
        <article class="w-full bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">

            <div class="px-5 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <i class="fas fa-calendar-day"></i>
                    </div>

                    <div>
                        <h2 class="font-bold text-gray-800">
                            Deadline Mendatang
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Dalam 7 hari ke depan
                        </p>
                    </div>
                </div>
            </div>

            <ul class="divide-y divide-gray-100 px-5 sm:px-6">

                @forelse($reminders as $item)
                    @php
                        $tglDeadline = \Carbon\Carbon::parse($item->deadline);
                        $selisihHari = (int) today()->diffInDays($tglDeadline->copy()->startOfDay(), false);

                        $labelDeadline = $selisihHari === 0
                            ? 'Hari ini'
                            : ($selisihHari === 1 ? 'Besok' : $selisihHari . ' hari lagi');

                        $skor = $item->skor_prioritas;

                        $labelPrioritas = $skor === null
                            ? 'Belum dihitung'
                            : ($skor >= 80 ? 'Tinggi' : ($skor >= 50 ? 'Sedang' : 'Rendah'));

                        $warnaPrioritas = $skor === null
                            ? 'bg-gray-100 text-gray-600'
                            : ($skor >= 80
                                ? 'bg-red-50 text-red-700'
                                : ($skor >= 50
                                    ? 'bg-amber-50 text-amber-700'
                                    : 'bg-green-50 text-green-700'));
                    @endphp

                    <li class="flex items-center justify-between gap-3 py-4">
                        <div class="min-w-0">
                            <a href="{{ url('/tugas/edit/' . $item->id_tugas) }}"
                               class="block font-semibold text-sm text-gray-800 hover:text-blue-700 break-words">
                                {{ $item->judul }}
                            </a>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $tglDeadline->format('d M Y') }}
                            </p>

                            <span class="inline-flex mt-2 rounded-full px-2.5 py-1 text-xs font-semibold {{ $warnaPrioritas }}">
                                Prioritas {{ $labelPrioritas }}
                                @if($skor !== null)
                                    · {{ round($skor) }}%
                                @endif
                            </span>
                        </div>

                        <span class="shrink-0 rounded-lg px-2.5 py-1.5 text-xs font-semibold
                            {{ $selisihHari === 0 ? 'bg-red-50 text-red-700' : 'bg-orange-50 text-orange-700' }}">
                            {{ $labelDeadline }}
                        </span>
                    </li>

                @empty
                    <li class="py-10 text-center">
                        <div class="mx-auto w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>

                        <p class="mt-3 font-semibold text-gray-700">
                            Tidak ada deadline dekat
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Belum ada tugas yang mendekati tenggat dalam 7 hari ke depan.
                        </p>
                    </li>
                @endforelse

            </ul>
        </article>

    </section>

    <p class="text-center text-xs text-gray-400 py-2">
        Minderly Task Management
    </p>

</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusCanvas = document.getElementById('statusChart');
    const priorityCanvas = document.getElementById('priorityChart');

    const statusData = @json($diagramStatus);
    const priorityData = @json($diagramPrioritas);

    if (typeof Chart === 'undefined') {
        console.error('Chart.js belum berhasil dimuat.');
        return;
    }

    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: statusData.labels,
                datasets: [{
                    data: statusData.data,
                    backgroundColor: ['#94a3b8', '#f59e0b', '#10b981'],
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 18
                        }
                    }
                }
            }
        });
    }

    if (priorityCanvas) {
        new Chart(priorityCanvas, {
            type: 'bar',
            data: {
                labels: priorityData.labels,
                datasets: [{
                    label: 'Jumlah tugas',
                    data: priorityData.data,
                    backgroundColor: ['#f43f5e', '#f59e0b', '#10b981', '#cbd5e1'],
                    borderRadius: 8,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    }
                }
            }
        });
    }
});
</script>

@endsection
