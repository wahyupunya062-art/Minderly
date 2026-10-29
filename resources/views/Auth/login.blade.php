<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Minderly</title>

    <style>
        * {
             margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html,
body {
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
    border-radius: 0;
    overflow: hidden;
    box-shadow: none;
}

/* Panel branding kiri */
.auth-brand {
    background: #075087;
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 50px;
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

/* Area form kanan */
.auth-form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%;
    max-width: 760px;
    margin: 0 auto;
    padding: clamp(30px, 7vw, 100px);
}

.form-heading {
    margin-bottom: 36px;
}

.form-heading h2 {
    font-size: clamp(28px, 3vw, 38px);
    font-weight: 750;
    margin-bottom: 12px;
    color: #263548;
}

.form-heading p {
    font-size: 15px;
    color: #718096;
    line-height: 1.8;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 650;
    margin-bottom: 10px;
    color: #394b60;
}

.form-group input {
    width: 100%;
    height: 54px;
    padding: 0 17px;
    border: 1px solid #d9e2ec;
    border-radius: 9px;
    outline: none;
    background: #fff;
    color: #263548;
    font-size: 15px;
}

.form-group input:focus {
    border-color: #075087;
    box-shadow: 0 0 0 3px rgba(7, 80, 135, 0.1);
}

.form-options {
    display: flex;
    justify-content: flex-end;
    margin-top: -8px;
    margin-bottom: 28px;
    font-size: 13px;
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
    min-height: 54px;
    border: none;
    border-radius: 9px;
    background: #075087;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-submit:hover {
    background: #063e69;
}

.auth-footer {
    text-align: center;
    margin-top: 28px;
    color: #718096;
    font-size: 14px;
}

.alert {
    padding: 13px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    line-height: 1.6;
}

.alert-error {
    background: #fff1f0;
    border: 1px solid #ffd6d2;
    color: #b42318;
}

.alert-success {
    background: #ecfdf3;
    border: 1px solid #abefc6;
    color: #067647;
}

/* Tampilan HP dan tablet */
@media (max-width: 760px) {
    .auth-container {
        grid-template-columns: 1fr;
        min-height: 100vh;
    }

    .auth-brand {
        min-height: 220px;
        padding: 30px 20px;
    }

    .brand-logo {
        font-size: 32px;
    }

    .brand-tag {
        margin-top: 16px;
    }

    .auth-form {
        max-width: 560px;
        padding: 40px 25px;
    }

    .form-heading {
        margin-bottom: 28px;
    }
}

        .form-heading h2 {
            font-size: 30px;
            font-weight: 750;
            margin-bottom: 10px;
            color: #263548;
        }

        .form-heading p {
            font-size: 14px;
            color: #718096;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 650;
            margin-bottom: 9px;
            color: #394b60;
        }

        .form-group input {
            width: 100%;
            height: 47px;
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

        .form-options {
            display: flex;
            justify-content: flex-end;
            margin-top: -7px;
            margin-bottom: 25px;
            font-size: 12px;
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
            min-height: 48px;
            border: none;
            border-radius: 9px;
            background: #075087;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .2s;
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
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-error {
            background: #fff1f0;
            border: 1px solid #ffd6d2;
            color: #b42318;
        }

        .alert-success {
            background: #ecfdf3;
            border: 1px solid #abefc6;
            color: #067647;
        }

        @media (max-width: 760px) {
            body {
                padding: 15px;
            }

            .auth-container {
                grid-template-columns: 1fr;
                max-width: 480px;
                min-height: auto;
            }

            .auth-brand {
                padding: 35px 20px;
                min-height: 190px;
            }

            .brand-logo {
                font-size: 30px;
            }

            .brand-tag {
                margin-top: 15px;
            }

            .auth-form {
                padding: 35px 25px;
            }

            .form-heading h2 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-container">

        <section class="auth-brand">
            <div class="brand-content">
                <div class="brand-logo">✓ MINDERLY</div>

                <p class="brand-subtitle">
                    Task Management System<br>
                    Atur tugas, kelola prioritas,<br>
                    dan capai tujuanmu.
                </p>

                <div class="brand-tag">
                    Plan smarter. Work better.
                </div>
            </div>
        </section>

        <section class="auth-form">
            <div class="form-heading">
                <h2>Sign In</h2>
                <p>
                    Selamat datang kembali!<br>
                    Masuk ke akunmu untuk melanjutkan.
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

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
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="form-options">
                   <a href="{{ route('password.request') }}"
                    class="text-sm font-medium text-blue-700 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" class="btn-submit">
                    Sign In &nbsp; →
                </button>
            </form>

            <div class="auth-footer">
                Belum punya akun?
                <a href="{{ route('register') }}">Sign Up</a>
            </div>
        </section>

    </main>
</body>
</html>
