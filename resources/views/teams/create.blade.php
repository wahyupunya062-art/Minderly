@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    {{-- Header halaman --}}
    <div>
        <a href="{{ route('teams.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800">
            &larr; Kembali ke Kolaborasi Tim
        </a>

        <h1 class="mt-4 text-2xl font-bold text-gray-800">
            Buat Tim Baru
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Isi informasi berikut untuk membuat tim baru.
        </p>
    </div>

    {{-- Pesan kesalahan --}}
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

    {{-- Formulir --}}
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">

        <form action="{{ route('teams.store') }}"
              method="POST"
              class="space-y-6">

            @csrf

            <div>
                <label for="name"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Tim <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    maxlength="100"
                    required
                    placeholder="Contoh: Tim Pengembangan Web"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <div>
                <label for="description"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Deskripsi Tim
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Jelaskan tujuan, tanggung jawab, atau kegiatan tim..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >{{ old('description') }}</textarea>

                <p class="mt-2 text-xs text-gray-500">
                    Deskripsi bersifat opsional.
                </p>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">

                <a href="{{ route('teams.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-3 font-semibold text-gray-700 transition hover:bg-gray-50">
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700">
                    + Buat Tim
                </button>

            </div>
        </form>
    </div>
</div>
@endsection