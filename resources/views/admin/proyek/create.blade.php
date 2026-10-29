<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Proyek | Minderly</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1e293b;
        }
        .container { max-width: 700px; margin: 0 auto; }
        .back { color: #4f46e5; text-decoration: none; }
        .card {
            margin-top: 22px;
            padding: 30px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 4px 18px #0f172a0a;
        }
        h1 { margin-bottom: 8px; }
        p { color: #64748b; line-height: 1.6; }
        label { display: block; margin: 22px 0 9px; font-weight: bold; }
        input, textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #d8deea;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
        }
        input:focus, textarea:focus {
            outline: 2px solid #c7d2fe;
            border-color: #6366f1;
        }
        textarea { min-height: 130px; resize: vertical; }
        .btn {
            display: inline-block;
            margin-top: 22px;
            padding: 13px 18px;
            background: #4f46e5;
            color: white;
            border: 0;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-secondary { background: #e2e8f0; color: #334155; margin-left: 8px; }
        .error { color: #dc2626; font-size: 13px; margin-top: 7px; }
        .alert { background: #fee2e2; color: #991b1b; padding: 13px; border-radius: 8px; }
    </style>
</head>
<body>
    <main class="container">
        <a class="back" href="{{ route('admin.proyek.index') }}">← Kembali ke proyek</a>

        <section class="card">
            <h1>Tambah Proyek Baru</h1>
            <p>Masukkan informasi proyek yang akan digunakan untuk pembagian tim.</p>

            @if($errors->any())
                <div class="alert">
                    <strong>Periksa kembali data berikut:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.proyek.store') }}" method="POST">
                @csrf

                <label for="nama_proyek">Nama Proyek *</label>
                <input
                    type="text"
                    id="nama_proyek"
                    name="nama_proyek"
                    maxlength="100"
                    value="{{ old('nama_proyek') }}"
                    placeholder="Contoh: Pengembangan Aplikasi Minderly"
                    required
                >

                @error('nama_proyek')
                    <div class="error">{{ $message }}</div>
                @enderror

                <label for="deskripsi">Deskripsi Proyek</label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    placeholder="Jelaskan tujuan dan gambaran proyek..."
                >{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn">Simpan Proyek</button>
                <a href="{{ route('admin.proyek.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </section>
    </main>
</body>
</html>