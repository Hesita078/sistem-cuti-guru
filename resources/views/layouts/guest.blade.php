<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login - Sistem Pengajuan Cuti Guru')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Lato', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Foto sekolah dari internet — tidak perlu file lokal */
            background-image: url('/images/school-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.50);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 10;
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 14px;
            padding: 40px 48px 44px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4), 0 8px 20px rgba(0,0,0,0.2);
            animation: fadeUp 0.6s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-header .header-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
            animation: pop 0.5s 0.3s cubic-bezier(.36,1.5,.6,1) both;
        }

        @keyframes pop {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .login-header h4 {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            letter-spacing: 1.5px;
            color: #2c2c2c;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .login-header small {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #7a9e7e;
        }

        .header-divider {
            width: 40px;
            height: 2px;
            background: #7a9e7e;
            margin: 14px auto 0;
            border-radius: 2px;
        }

        .login-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-align: center;
            color: #444;
            margin-bottom: 24px;
            text-transform: uppercase;
        }

        .alert {
            border-radius: 8px;
            font-size: 13px;
            padding: 10px 14px;
            border: none;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 7px;
        }

        .input-group {
            border: 1.5px solid #ddd;
            border-radius: 8px !important;
            overflow: hidden;
            transition: border-color 0.25s, box-shadow 0.25s;
        }

        .input-group:focus-within {
            border-color: #7a9e7e;
            box-shadow: 0 0 0 3px rgba(122,158,126,0.15);
        }

        .input-group-text {
            background: #fafafa !important;
            border: none !important;
            padding: 10px 14px;
            color: #7a9e7e;
        }

        .input-group .form-control {
            border: none !important;
            border-radius: 0 !important;
            background: #fafafa;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            color: #333;
            padding: 11px 14px;
            box-shadow: none !important;
        }

        .input-group .form-control::placeholder { color: #aaa; }
        .input-group .form-control:focus { background: #fff; }
        .input-group .form-control.is-invalid {
            border-left: 2px solid #dc3545 !important;
        }

        small.text-danger {
            font-size: 11px;
            margin-top: 5px;
            display: block;
        }

        .form-check-input:checked {
            background-color: #7a9e7e;
            border-color: #7a9e7e;
        }

        .form-check-label {
            font-size: 13px;
            color: #666;
        }

        .btn-login {
            display: block;
            width: 100%;
            padding: 13px;
            margin-top: 20px;
            background: #7a9e7e;
            color: #fff !important;
            border: none;
            border-radius: 8px !important;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.25s, transform 0.15s, box-shadow 0.25s;
            box-shadow: 0 4px 14px rgba(122,158,126,0.4);
        }

        .btn-login:hover {
            background: #6a8e6e;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(122,158,126,0.5);
            color: #fff;
        }

        .btn-login:active { transform: translateY(0); }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #bbb;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }

        .btn-eye {
            background: #fafafa !important;
            border: none !important;
            border-left: 1px solid #eee !important;
            cursor: pointer;
            color: #7a9e7e;
            padding: 10px 14px;
            transition: color 0.2s;
        }

        .btn-eye:hover { color: #5a7e5e; }
    </style>

    @stack('styles')
</head>
<body>
    <div class="login-container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
