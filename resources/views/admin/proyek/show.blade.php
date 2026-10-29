<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Proyek | Minderly</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1e293b;
        }

        .navbar {
            background: white;
            padding: 18px 6%;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            color: #4f46e5;
            font-weight: bold;
            font-size: 23px;
        }

        .container {
            max-width: 950px;
            margin: 32px auto;
            padding: 0 18px;
        }

        .back {
            color: #4f46e5;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            margin-top: 20px;
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 18px #0f172a0a;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        p {
            color: #64748b;
            line-height: 1.7;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin: 24px 0;
        }

        .stat {
            background: #eef2ff;
            padding: 20px;
            border-radius: 10px;
        }

        .stat span {
            color: #6366f1;
            font-size: 14px;
        }

        .stat h2 {
            margin-bottom: 0;
            font-size: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 17px;
            border: 0;
            border-radius: 8px;
            background: #4f46e5;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
        }

        .btn:hover {
            background: #4338ca;
        }

        .btn-secondary {
            background: #64748b;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .alert {
            padding: 14px;
            border-radius: 8px;
            margin: 15px 0;
            background: #dcfce7;
            color: #166534;
            overflow-wrap: anywhere;
        }

        .warning {
            padding: 14px;
            border-radius: 8px;
            margin: 15px 0;
            background: #fef3c7;
            color: #92400e;
            line-height: 1.6;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .section-title {
            margin-top: 25px;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 16px 5%;
            }

            .card {
                padding: 18px;
            }

            .brand {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="brand">Minderly</div>
        <div>Panel Admin</div>
    </nav>

    <main class="container">

        <a class="back" href="{{ route('admin.proyek.index') }}">
            &larr; Kembali ke daftar proyek
        </a>

        {{-- Pesan berhasil --}}
        @if (session('success'))
            <div class="alert" role="status">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan gagal --}}
        @if (session('error'))
            <div class="alert error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Pesan validasi --}}
        @if ($errors->any())
            <div class="alert error" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card">

            <h1>{{ $proyek->nama_proyek }}</h1>

            <p>
                {{ $proyek->deskripsi ?: 'Belum ada deskripsi proyek.' }}
            </p>

            <div class="stats">
                <div class="stat">
                    <span>Jumlah Tim</span>
                    <h2>{{ $jumlahTim }}</h2>
                </div>

                <div class="stat">
                    <span>Jumlah Anggota Proyek</span>
                    <h2>{{ $jumlahAnggota }}</h2>
                </div>
            </div>

            <hr style="border:0; border-top:1px solid #e5e7eb; margin:25px 0;">

            <h2 class="section-title">Pembagian Tim Otomatis</h2>

            <p>
                Sistem akan membagi peserta yang memiliki profil kuis lengkap
                ke dalam tim dengan maksimal lima orang per tim.
                Ketua tim dipilih berdasarkan gabungan skor perencanaan
                dan kolaborasi tertinggi di dalam tim.
            </p>

            {{-- Jika proyek sudah mempunyai tim --}}
            @if ($jumlahTim > 0)

                <div class="warning">
                    <strong>Pembagian tim sudah dilakukan.</strong><br>
                    Proyek ini sudah memiliki {{ $jumlahTim }} tim.
                    Pembagian ulang tidak dapat dilakukan melalui tombol ini
                    untuk menghindari tim atau anggota duplikat.
                </div>

                <a href="{{ route('admin.proyek.index') }}"
                   class="btn btn-secondary">
                    Kembali ke Daftar Proyek
                </a>

            {{-- Jika proyek belum mempunyai tim --}}
            @else

                <div class="warning">
                    <strong>Periksa sebelum memulai.</strong><br>
                    Pastikan peserta sudah mengisi semua nilai profil kuis.
                    Pembagian tim akan dijalankan setelah kamu mengonfirmasi.
                </div>

                <form
                    action="{{ route('admin.tim.assign') }}"
                    method="POST"
                    onsubmit="return confirm('Apakah kamu yakin ingin membagi peserta ke dalam tim secara otomatis?');"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="id_proyek"
                        value="{{ $proyek->id_proyek }}"
                    >

                    <button type="submit" class="btn">
                        Bagi Tim Otomatis
                    </button>
                </form>

            @endif

        </section>
    </main>
</body>
</html>