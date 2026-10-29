<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Proyek | Minderly</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1e293b;
        }
        .navbar {
            background: #fff;
            padding: 18px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
        }
        .brand { font-size: 23px; font-weight: bold; color: #4f46e5; }
        .container { max-width: 1100px; margin: 35px auto; padding: 0 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }
        h1 { margin: 0 0 8px; font-size: 28px; }
        p { color: #64748b; line-height: 1.6; }
        .btn {
            display: inline-block;
            padding: 12px 17px;
            border-radius: 8px;
            border: 0;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background: #4338ca; }
        .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 4px 18px #0f172a0a;
            overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { padding: 15px 12px; text-align: left; border-bottom: 1px solid #edf0f5; }
        th { background: #f8fafc; color: #64748b; font-size: 13px; }
        td { font-size: 14px; }
        .link { color: #4f46e5; text-decoration: none; font-weight: bold; }
        .alert { padding: 14px; margin-bottom: 18px; border-radius: 8px; background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .empty { text-align: center; padding: 35px; color: #64748b; }
        .badge { background: #eef2ff; color: #4338ca; padding: 5px 9px; border-radius: 20px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="brand">Minderly</div>
        <div>Panel Admin</div>
    </nav>

    <main class="container">
        <div class="header">
            <div>
                <h1>Kelola Proyek</h1>
                <p>Kelola proyek dan pembagian anggota tim secara otomatis.</p>
            </div>
            <a href="{{ route('admin.proyek.create') }}" class="btn">
                + Tambah Proyek
            </a>
        </div>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="card">
            <h3>Daftar Proyek</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Dibuat</th>
                        <th>Status Tim</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyek as $item)
                        <tr>
                            <td><strong>{{ $item->nama_proyek }}</strong></td>
                            <td>{{ \Illuminate\Support\Str::limit($item->deskripsi ?: '-', 60) }}</td>
                            <td>{{ $item->dibuat_pada ? $item->dibuat_pada->format('d/m/Y') : '-' }}</td>
                            <td><span class="badge">Proyek tersimpan</span></td>
                            <td>
                                <a class="link" href="{{ route('admin.proyek.show', $item->id_proyek) }}">
                                    Lihat Detail →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">
                                <strong>Belum ada proyek</strong>
                                <p>Buat proyek terlebih dahulu untuk memulai pembagian tim.</p>
                                <a href="{{ route('admin.proyek.create') }}" class="btn">Buat Proyek</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>