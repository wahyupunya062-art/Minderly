
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minderly</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#EFF7FF] text-gray-800 font-sans antialiased">
<div class="flex min-h-screen">
    <!-- SIDEBAR -->
    <aside class="w-64 min-h-screen bg-[#064B87] px-5 py-6 flex-shrink-0 text-white flex flex-col">
        <!-- LOGO -->
        <div class="mb-9 px-2">
            <h1 class="text-xl font-bold tracking-wide text-white">✓ MINDERLY</h1>
            <p class="text-xs text-blue-200 mt-1">Task Management System</p>
        </div>

        <!-- PROFILE -->
        <!-- PROFILE -->
<div class="mb-8 rounded-xl bg-white/10 border border-white/10 p-3">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-200 text-[#064B87]
                    flex items-center justify-center font-bold">
            {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 2)) }}
        </div>

        <div class="min-w-0">
            <h2 class="font-semibold text-sm text-white truncate">
                {{ auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna' }}
            </h2>

            <p class="text-xs text-blue-200 mt-1">
                {{ auth()->user()->profesi ?? 'Pengguna' }}
            </p>
        </div>
    </div>
</div>

        <!-- MENU -->
        <nav class="space-y-1">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9v9a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-sm">Dashboard</span>
            </a>

            
            <a href="/tugas" class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M9 5h6M9 9h6M9 13h6M5 5h.01M5 9h.01M5 13h.01"/>
                 </svg>
                <span class="text-sm">Manajemen Tugas</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m8-4a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-sm">Kolaborasi Tim</span>
            </a>

            <a href="{{ route('kalender.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm">Kalender & Jadwal</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z"/>
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.7 1.7 0 00-1.88-.34 1.7 1.7 0 00-1.03 1.56V21a2 2 0 01-4 0v-.09a1.7 1.7 0 00-1.03-1.56 1.7 1.7 0 00-1.88.34l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.7 1.7 0 004.6 15a1.7 1.7 0 00-1.56-1H3a2 2 0 010-4h.09a1.7 1.7 0 001.56-1 1.7 1.7 0 00-.34-1.88l-.06-.06A2 2 0 017.08 4.23l.06.06a1.7 1.7 0 001.88.34H9a1.7 1.7 0 001-1.56V3a2 2 0 014 0v.09a1.7 1.7 0 001 1.56 1.7 1.7 0 001.88-.34l.06-.06a2 2 0 012.83 2.83l-.06.06a1.7 1.7 0 00-.34 1.88V9c0 .66.39 1.25 1 1.5.18.08.37.1.56.1H21a2 2 0 010 4h-.09c-.66 0-1.25.39-1.51 1z"/>
                </svg>
                <span class="text-sm">Pengaturan Akun</span>
            </a>
        </nav>

        <!-- SISTEM -->
        <div class="mt-8">
            <p class="text-xs font-bold text-blue-200 mb-3 px-3 tracking-wider">SISTEM</p>

            <a href="{{ route('notifikasi.index') }}"
            class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        d="M15 17h5l-1.5-2V11a6.5 6.5 0 10-13 0v4L4 17h5"/>
                </svg>
                <span class="text-sm">Notifikasi</span>
            </a>

            <a href="{{ route('riwayat.index') }}"class="flex items-center gap-3 px-3 py-3 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm">Riwayat Aktivitas</span>
            </a>

            
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf

                <button type="submit" class="flex items-center gap-3 px-3 py-3 rounded-xl text-red-200 hover:bg-red-500/20 hover:text-white transition w-full text-left bg-transparent border-0 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                    </svg>

                    <span class="text-sm">Log Out</span>
                </button>
            </form>
        </div>

        <div class="mt-auto pt-8 px-3">
            <p class="text-xs text-blue-200">© 2026 Minderly</p>
            <p class="text-xs text-blue-300 mt-1">Task Management System</p>
        </div>
    </aside>

    <!-- CONTENT -->
    <div class="flex-1 min-w-0">
        <!-- HEADER -->
        <header class="h-[68px] bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button type="button" class="text-gray-500 hover:text-[#064B87] transition" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
               <h1 class="font-bold text-lg text-gray-800"> @yield('header', 'Dashboard') </h1>
            </div>

            <div class="flex items-center gap-4">
        
<form action="{{ url('/tugas') }}" method="GET"
    class="relative hidden sm:block">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari Tugas..."
        autocomplete="off"
        class="w-56 lg:w-72 border border-gray-200 bg-gray-50 rounded-xl pl-10 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
    >

    <button
        type="submit"
        aria-label="Cari tugas"
        class="absolute left-0 top-0 h-full pl-3 flex items-center text-gray-400 hover:text-blue-600"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7" stroke-width="2"/>
            <path stroke-width="2" stroke-linecap="round" d="m16 16 4 4"/>
        </svg>
    </button>
</form>

                
<div class="relative" id="notificationDropdown">
    <button
        type="button"
        id="notificationButton"
        class="relative w-9 h-9 rounded-xl hover:bg-gray-100 flex items-center justify-center text-gray-500"
        aria-label="Notifikasi"
        aria-expanded="false"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M18 8a6 6 0 00-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"
            />
        </svg>

        @if(($jumlahNotifikasiHeader ?? 0) > 0)
            <span class="absolute top-1 right-1 min-w-4 h-4 px-1 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center">
                {{ $jumlahNotifikasiHeader > 9 ? '9+' : $jumlahNotifikasiHeader }}
            </span>
        @endif
    </button>

    <div
        id="notificationPanel"
        class="hidden absolute right-0 top-full mt-3 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden"
    >
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Notifikasi</h3>
            <span class="text-xs text-gray-500">
                {{ $jumlahNotifikasiHeader ?? 0 }} tugas
            </span>
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse(($notifikasiHeader ?? collect()) as $item)
                <a
                    href="{{ url('/tugas/edit/' . $item->id_tugas) }}"
                    class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition"
                >
                    <p class="text-sm font-medium text-gray-800">
                        {{ $item->judul }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        @if($item->deadline && \Carbon\Carbon::parse($item->deadline)->isPast() && !\Carbon\Carbon::parse($item->deadline)->isToday())
                            Deadline sudah lewat
                        @elseif($item->deadline && \Carbon\Carbon::parse($item->deadline)->isToday())
                            Deadline hari ini
                        @elseif($item->deadline)
                            Deadline: {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}
                        @else
                            Prioritas tinggi
                        @endif
                    </p>
                </a>
            @empty
                <div class="px-4 py-8 text-center">
                    <p class="text-sm text-gray-500">Tidak ada notifikasi saat ini.</p>
                </div>
            @endforelse
        </div>

        <a
            href="{{ route('notifikasi.index') }}"
            class="block px-4 py-3 text-center text-sm font-semibold text-blue-600 hover:bg-blue-50 transition"
        >
            Lihat semua notifikasi
        </a>
    </div>
</div>


                <div class="w-9 h-9 rounded-full bg-blue-100 text-[#064B87]
            flex items-center justify-center font-bold text-sm">
    {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 2)) }}
</div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="p-5 md:p-7 overflow-x-hidden">
            @yield('content')
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('notificationButton');
    const panel = document.getElementById('notificationPanel');
    const wrapper = document.getElementById('notificationDropdown');

    if (!button || !panel || !wrapper) return;

    function tutupDropdown() {
        panel.classList.add('hidden');
        button.setAttribute('aria-expanded', 'false');
    }

    button.addEventListener('click', function (event) {
        event.stopPropagation();

        const akanDibuka = panel.classList.contains('hidden');

        panel.classList.toggle('hidden', !akanDibuka);
        button.setAttribute('aria-expanded', String(akanDibuka));
    });

    document.addEventListener('click', function (event) {
        if (!wrapper.contains(event.target)) {
            tutupDropdown();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            tutupDropdown();
        }
    });
});
</script>

</body>
</html>
