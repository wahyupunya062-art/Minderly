
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Minderly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-5">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl">
        <a href="{{ route('login') }}"
           class="text-sm text-blue-700 hover:underline">
            ← Kembali ke Login
        </a>

        <div class="mt-8 mb-6">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-2xl">
                🔐
            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                Lupa Password?
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Jangan khawatir! Masukkan email yang kamu gunakan
                untuk mendaftar Minderly. Kami akan membantu kamu
                mengatur password baru.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-green-50 p-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                Alamat Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
                autofocus
                autocomplete="email"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
            >

            <button
                type="submit"
                class="mt-5 w-full rounded-xl bg-blue-700 px-4 py-3 font-semibold text-white transition hover:bg-blue-800"
            >
                Kirim Tautan Reset
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Ingat password?
            <a href="{{ route('login') }}"
               class="font-semibold text-blue-700 hover:underline">
                Login di sini
            </a>
        </p>
    </div>
</body>
</html>
