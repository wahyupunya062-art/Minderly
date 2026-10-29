@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Kalender & Jadwal
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Atur agenda rutin dan kegiatan khususmu.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
            <p class="mb-2 font-semibold">
                Periksa kembali isian berikut:
            </p>
            <ul class="list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM TAMBAH AGENDA --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800">
            Tambah Agenda
        </h2>
        <p class="mt-1 mb-5 text-sm text-gray-500">
            Simpan jadwal kuliah, sekolah, rapat, atau kegiatan lainnya.
        </p>

        <form action="{{ route('kalender.store') }}"
              method="POST"
              class="space-y-5">
            @csrf

            <div>
                <label for="nama_agenda"
                       class="mb-1 block text-sm font-semibold text-gray-700">
                    Nama agenda
                </label>
                <input id="nama_agenda"
                       name="nama_agenda"
                       type="text"
                       value="{{ old('nama_agenda') }}"
                       placeholder="Contoh: Kuliah Pemrograman Web"
                       maxlength="150"
                       required
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="jenis"
                       class="mb-1 block text-sm font-semibold text-gray-700">
                    Jenis agenda
                </label>
                <select id="jenis"
                        name="jenis"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="rutin"
                        @selected(old('jenis', 'rutin') === 'rutin')>
                        Agenda rutin — berulang setiap minggu
                    </option>
                    <option value="tambahan"
                        @selected(old('jenis') === 'tambahan')>
                        Agenda tambahan — satu kali
                    </option>
                </select>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="tanggal_mulai"
                           class="mb-1 block text-sm font-semibold text-gray-700">
                        Tanggal mulai
                    </label>
                    <input id="tanggal_mulai"
                           name="tanggal_mulai"
                           type="date"
                           value="{{ old('tanggal_mulai', now()->toDateString()) }}"
                           required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                </div>

                <div id="tanggal-selesai-wrapper">
                    <label for="tanggal_selesai"
                           class="mb-1 block text-sm font-semibold text-gray-700">
                        Tanggal berakhir (opsional)
                    </label>
                    <input id="tanggal_selesai"
                           name="tanggal_selesai"
                           type="date"
                           value="{{ old('tanggal_selesai') }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                </div>
            </div>

            <div id="hari-rutin-wrapper">
                <p class="mb-2 text-sm font-semibold text-gray-700">
                    Hari berulang
                </p>

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    @foreach ([
                        'Senin', 'Selasa', 'Rabu', 'Kamis',
                        'Jumat', 'Sabtu', 'Minggu'
                    ] as $hari)
                        <label class="flex items-center gap-2 rounded-xl border border-gray-200 p-3 text-sm text-gray-700 hover:bg-blue-50">
                            <input type="checkbox"
                                   name="hari_rutin[]"
                                   value="{{ $hari }}"
                                   @checked(in_array(
                                       $hari,
                                       old('hari_rutin', [
                                           'Senin', 'Selasa', 'Rabu',
                                           'Kamis', 'Jumat', 'Sabtu'
                                       ])
                                   ))
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            {{ $hari }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="jam_mulai"
                           class="mb-1 block text-sm font-semibold text-gray-700">
                        Jam mulai
                    </label>
                    <input id="jam_mulai"
                           name="jam_mulai"
                           type="time"
                           value="{{ old('jam_mulai', '08:00') }}"
                           required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                </div>

                <div>
                    <label for="jam_selesai"
                           class="mb-1 block text-sm font-semibold text-gray-700">
                        Jam selesai
                    </label>
                    <input id="jam_selesai"
                           name="jam_selesai"
                           type="time"
                           value="{{ old('jam_selesai', '10:00') }}"
                           required
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
                </div>
            </div>

            <div>
                <label for="lokasi"
                       class="mb-1 block text-sm font-semibold text-gray-700">
                    Lokasi (opsional)
                </label>
                <input id="lokasi"
                       name="lokasi"
                       type="text"
                       value="{{ old('lokasi') }}"
                       placeholder="Contoh: Ruang kelas 204"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">
            </div>

            <div>
                <label for="deskripsi"
                       class="mb-1 block text-sm font-semibold text-gray-700">
                    Keterangan (opsional)
                </label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          rows="3"
                          placeholder="Tambahkan catatan kegiatan..."
                          class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-4">
                <button type="submit"
                        class="w-full rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 sm:w-auto">
                    Simpan Agenda
                </button>
            </div>
        </form>
    </div>

    {{-- DAFTAR AGENDA --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-800">
                Agenda Saya
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Daftar kegiatan yang tersimpan pada akunmu.
            </p>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse ($agendas as $agenda)
                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            <h3 class="font-semibold text-gray-800">
                                {{ $agenda->nama_agenda }}
                            </h3>

                            <span class="rounded-full px-3 py-1 text-xs font-medium
                                {{ $agenda->jenis === 'rutin'
                                    ? 'bg-green-50 text-green-700'
                                    : 'bg-purple-50 text-purple-700' }}">
                                {{ $agenda->jenis === 'rutin'
                                    ? 'Agenda Rutin'
                                    : 'Agenda Tambahan' }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600">
                            {{ $agenda->jam_mulai }}–{{ $agenda->jam_selesai }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            Mulai:
                            {{ $agenda->tanggal_mulai->format('d M Y') }}
                            @if ($agenda->tanggal_selesai)
                                — sampai
                                {{ $agenda->tanggal_selesai->format('d M Y') }}
                            @endif
                        </p>

                        @if ($agenda->jenis === 'rutin')
                            <p class="mt-1 text-sm text-gray-500">
                                Setiap: {{ implode(', ', $agenda->hari_rutin ?? []) }}
                            </p>
                        @endif

                        @if ($agenda->lokasi)
                            <p class="mt-1 text-sm text-gray-500">
                                <span class="font-medium">Lokasi:</span>
                                {{ $agenda->lokasi }}
                            </p>
                        @endif

                        @if ($agenda->deskripsi)
                            <p class="mt-2 whitespace-pre-line text-sm text-gray-600">
                                {{ $agenda->deskripsi }}
                            </p>
                        @endif
                    </div>

                    <form action="{{ route('kalender.destroy', $agenda) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus agenda ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-10 text-center">
                    <div class="mb-3 text-3xl">📅</div>
                    <p class="font-semibold text-gray-700">
                        Belum ada agenda
                    </p>
                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan agenda pertamamu melalui formulir di atas.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenis = document.getElementById('jenis');
        const hariWrapper = document.getElementById('hari-rutin-wrapper');
        const selesaiWrapper = document.getElementById('tanggal-selesai-wrapper');
        const hariInputs = hariWrapper.querySelectorAll('input[type="checkbox"]');

        function updateJenisAgenda() {
            const rutin = jenis.value === 'rutin';

            hariWrapper.hidden = !rutin;
            selesaiWrapper.hidden = !rutin;

            hariInputs.forEach(function (input) {
                input.disabled = !rutin;
            });
        }

        jenis.addEventListener('change', updateJenisAgenda);
        updateJenisAgenda();
    });
</script>
@endsection
