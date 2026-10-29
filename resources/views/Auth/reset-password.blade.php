
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Password Baru - Minderly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-5">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
        <a href="{{ route('login') }}"
           class="text-sm text-blue-700 hover:underline">
            ← Kembali ke Login
        </a>

        <h1 class="mt-8 text-2xl font-bold text-slate-800">
            Buat Password Baru
        </h1>

        <p class="mt-2 mb-6 text-sm leading-6 text-slate-500">
            Buat password baru yang kuat agar akun Minderly kamu tetap aman.
        </p>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                Alamat Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email', $email) }}"
                required
                autocomplete="email"
                class="mb-5 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
            >

            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                Password Baru
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Minimal 8 karakter"
                class="mb-5 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
            >

            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                Konfirmasi Password Baru
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                minlength="8"
                autocomplete="new-password"
                placeholder="Ulangi password baru"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
            >

            <button
                type="submit"
                class="mt-6 w-full rounded-xl bg-blue-700 px-4 py-3 font-semibold text-white transition hover:bg-blue-800"
            >
                Simpan Password Baru
            </button>
        </form>
    </div>
</body>
</html>
