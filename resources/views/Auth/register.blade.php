<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Minderly</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            min-height: 100%;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #eaf6ff;
            color: #263548;
        }

        .auth-container {
            display: grid;
            grid-template-columns: 40% 60%;
            width: 100%;
            min-height: 100vh;
            background: #fff;
            overflow: hidden;
        }

        /* PANEL BRANDING */
        .auth-brand {
            background: #075087;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .auth-brand::before,
        .auth-brand::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .auth-brand::before {
            width: 420px;
            height: 420px;
            top: -220px;
            left: -180px;
        }

        .auth-brand::after {
            width: 500px;
            height: 500px;
            bottom: -300px;
            right: -250px;
        }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-size: clamp(32px, 3vw, 48px);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 18px;
        }

        .brand-subtitle {
            color: #dceeff;
            font-size: 16px;
            line-height: 1.9;
        }

        .brand-tag {
            display: inline-block;
            margin-top: 32px;
            padding: 10px 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            font-size: 13px;
        }

        /* FORM REGISTER */
        .auth-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
            padding: clamp(30px, 6vw, 85px);
        }

        .form-heading {
            margin-bottom: 30px;
        }

        .form-heading h2 {
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 750;
            margin-bottom: 10px;
            color: #263548;
        }

        .form-heading p {
            font-size: 14px;
            color: #718096;
            line-height: 1.8;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 650;
            margin-bottom: 8px;
            color: #394b60;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            padding: 0 15px;
            border: 1px solid #d9e2ec;
            border-radius: 9px;
            outline: none;
            background: #fff;
            color: #263548;
            font-size: 14px;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-group input::placeholder {
            color: #a0aec0;
        }

        .form-group input:focus {
            border-color: #075087;
            box-shadow: 0 0 0 3px rgba(7, 80, 135, .10);
        }

        a {
            color: #075087;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            min-height: 50px;
            border: none;
            border-radius: 9px;
            background: #075087;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .2s;
            margin-top: 5px;
        }

        .btn-submit:hover {
            background: #063e69;
            transform: translateY(-1px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 25px;
            color: #718096;
            font-size: 13px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-error {
            background: #fff1f0;
            border: 1px solid #ffd6d2;
            color: #b42318;
        }

        /* RESPONSIVE */
        @media (max-width: 760px) {
            .auth-container {
                grid-template-columns: 1fr;
            }

            .auth-brand {
                min-height: 200px;
                padding: 30px 20px;
            }

            .brand-logo {
                font-size: 32px;
            }

            .brand-tag {
                margin-top: 15px;
            }

            .auth-form {
                max-width: 560px;
                padding: 35px 25px;
            }

            .form-heading {
                margin-bottom: 25px;
            }

            .form-heading h2 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-container">

        <!-- PANEL KIRI -->
        <section class="auth-brand">
            <div class="brand-content">
                <div class="brand-logo">✓ MINDERLY</div>

                <p class="brand-subtitle">
                    Task Management System<br>
                    Mulai atur tugas dengan lebih terarah,<br>
                    dan temukan cara kerja terbaikmu.
                </p>

                <div class="brand-tag">
                    Plan smarter. Work better.
                </div>
            </div>
        </section>

        <!-- FORM KANAN -->
        <section class="auth-form">

            <div class="form-heading">
                <h2>Create Account</h2>
                <p>
                    Buat akun Minderly sekarang.<br>
                    Mulai perjalananmu mengelola tugas dengan lebih baik.
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        placeholder="Nama lengkap"
                        value="{{ old('nama') }}"
                        autocomplete="name"
                        maxlength="100"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Konfirmasi Password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button type="submit" class="btn-submit">
                    Create Account &nbsp; →
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Sign In</a>
            </div>

        </section>
    </main>
</body>
</html>