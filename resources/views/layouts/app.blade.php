<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Pengajuan Cuti dan Izin — SDN Kincang 01')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-800:  #2b4a8f;
            --blue-700:  #3b5fc4;
            --blue-600:  #4f75d8;
            --blue-500:  #6b8fe8;
            --blue-300:  #a8c0f5;
            --blue-200:  #c5d7fa;
            --blue-100:  #dce8fd;
            --blue-50:   #eef4ff;

            --peri-700:  #5058b8;
            --peri-500:  #7b85d8;
            --peri-200:  #c4c8f0;
            --peri-100:  #e0e2f8;
            --peri-50:   #f0f1fc;

            --sky-200:   #bae0f7;
            --sky-100:   #d9f0fc;
            --sky-50:    #eef8ff;

            --green-600: #4a9e72;
            --green-100: #d4f0e2;
            --green-50:  #edfaf3;

            --peach-600: #c97a50;
            --peach-100: #fce3ce;
            --peach-50:  #fff4ec;

            --rose-600:  #c45f6e;
            --rose-100:  #fad5da;
            --rose-50:   #fff0f2;

            --gray-900: #1e2235;
            --gray-800: #2d3250;
            --gray-700: #424770;
            --gray-600: #5a6090;
            --gray-500: #7a80a8;
            --gray-400: #9ea4c4;
            --gray-300: #c0c5de;
            --gray-200: #dce0f0;
            --gray-100: #edeff8;
            --gray-50:  #f5f6fc;
            --white:    #ffffff;

            --sb-bg:          #eef1fb;
            --sb-border:      #d8ddf4;
            --sb-text:        #5a6090;
            --sb-text-muted:  #9ea4c4;
            --sb-active-bg:   #dce2f9;
            --sb-active-text: #3b5fc4;
            --sb-hover-bg:    #e6eaf8;
            --sb-label:       #b0b6d4;

            --page-bg: #ffffff;

            --sidebar-w: 256px;
            --topbar-h:  64px;
            --r-sm: 8px;
            --r-md: 12px;
            --r-lg: 18px;
            --r-xl: 24px;

            --shadow-xs: 0 1px 4px rgba(60,80,180,.07);
            --shadow-sm: 0 2px 10px rgba(60,80,180,.09);
            --shadow-md: 0 4px 22px rgba(60,80,180,.13);
            --shadow-lg: 0 8px 36px rgba(60,80,180,.17);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #ffffff;
            color: var(--gray-900);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* PAGE LOADER */
        #page-loader {
            position: fixed; inset: 0;
            background: rgba(238,241,251,.9);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }
        #page-loader .loader-ring {
            width: 44px; height: 44px;
            border-radius: 50%;
            border: 3px solid var(--blue-200);
            border-top-color: var(--blue-600);
            animation: spin .8s linear infinite;
        }
        #page-loader .loader-text {
            font-size: 13px; font-weight: 500;
            color: var(--gray-500);
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* SIDEBAR */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sb-bg);
            border-right: 1.5px solid var(--sb-border);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            box-shadow: var(--shadow-sm);
        }
        .sidebar-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 0 12px 20px;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar-inner::-webkit-scrollbar { display: none; }

        /* Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 20px 8px 16px;
            border-bottom: 1px solid var(--sb-border);
            margin-bottom: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 38px; height: 38px;
            border-radius: var(--r-md);
            background: linear-gradient(135deg, var(--blue-500), var(--blue-700));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(75,117,216,.32);
        }
        .brand-icon i { font-size: 18px; color: white; }
        .brand-text strong {
            display: block;
            font-size: 13.5px; font-weight: 700;
            color: var(--gray-800);
            line-height: 1.25; letter-spacing: -.01em;
        }
        .brand-text span {
            font-size: 11px;
            color: var(--sb-text-muted);
        }

        /* Sidebar user card */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 12px;
            padding: 10px 12px;
            background: var(--white);
            border: 1px solid var(--sb-border);
            border-radius: var(--r-md);
        }
        .su-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-500), var(--peri-700));
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 13px; font-weight: 700;
            flex-shrink: 0;
        }
        .su-info strong {
            display: block;
            font-size: 12.5px; font-weight: 600;
            color: var(--gray-800); line-height: 1.25;
        }
        .su-info span {
            font-size: 11px;
            color: var(--sb-text-muted);
            text-transform: capitalize;
        }

        /* Nav label */
        .nav-label {
            font-size: 10px; font-weight: 700;
            letter-spacing: .10em; text-transform: uppercase;
            color: var(--sb-label);
            padding: 14px 8px 5px;
        }

        /* Nav items */
        .sidebar-nav { list-style: none; }
        .sidebar-nav li { margin-bottom: 2px; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 11px;
            border-radius: var(--r-md);
            color: var(--sb-text);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all .18s;
            position: relative;
        }
        .sidebar-nav a i {
            font-size: 16px; width: 20px;
            text-align: center; flex-shrink: 0;
        }
        .sidebar-nav a:hover {
            background: var(--sb-hover-bg);
            color: var(--gray-800);
        }
        .sidebar-nav a.active {
            background: var(--sb-active-bg);
            color: var(--sb-active-text);
            font-weight: 600;
        }
        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 56%;
            background: var(--blue-600);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav-wrap {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar-nav-wrap::-webkit-scrollbar { display: none; }

        /* Logout */
        .sidebar-footer {
            padding-top: 10px;
            border-top: 1px solid var(--sb-border);
            flex-shrink: 0;
        }
        .btn-logout {
            display: flex; align-items: center; gap: 10px;
            width: 100%;
            padding: 9px 11px;
            background: transparent;
            border: 1px solid var(--gray-200);
            border-radius: var(--r-md);
            color: var(--gray-500);
            font-size: 13.5px; font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all .18s;
        }
        .btn-logout i { font-size: 16px; }
        .btn-logout:hover {
            background: var(--rose-100);
            border-color: #f0b8c0;
            color: var(--rose-600);
        }

        /* LOGOUT MODAL */
        #logout-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30,34,80,.45);
            z-index: 9998;
            align-items: center;
            justify-content: center;
        }
        .logout-modal-box {
            background: #fff;
            border-radius: 18px;
            padding: 2rem;
            width: 360px;
            max-width: 90%;
            box-shadow: 0 8px 36px rgba(60,80,180,.2);
            animation: modalIn .2s ease;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .logout-modal-icon {
            width: 48px; height: 48px;
            border-radius: var(--r-md);
            background: var(--rose-100);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logout-modal-icon i { font-size: 22px; color: var(--rose-600); }
        .logout-modal-title {
            font-size: 15px; font-weight: 700;
            color: var(--gray-900); margin: 0;
        }
        .logout-modal-sub {
            font-size: 12px; color: var(--gray-500); margin: 0;
        }
        .logout-modal-desc {
            font-size: 13.5px; color: var(--gray-600);
            line-height: 1.6; margin: 1rem 0 1.5rem;
        }
        .logout-modal-actions { display: flex; gap: 10px; }
        .btn-modal-cancel {
            flex: 1; padding: 10px;
            border-radius: var(--r-md);
            border: 1.5px solid var(--gray-200);
            background: var(--gray-100);
            color: var(--gray-700);
            font-family: 'DM Sans', sans-serif;
            font-weight: 600; font-size: 13.5px;
            cursor: pointer; transition: all .18s;
        }
        .btn-modal-cancel:hover { background: var(--gray-200); }
        .btn-modal-confirm {
            flex: 1; padding: 10px;
            border-radius: var(--r-md);
            border: none;
            background: var(--rose-600);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600; font-size: 13.5px;
            cursor: pointer; transition: all .18s;
        }
        .btn-modal-confirm:hover { background: #b0505e; }

        /* MAIN WRAP */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #ffffff;
        }

        /* TOPBAR */
        .topbar {
            height: var(--topbar-h);
            background: var(--white);
            border-bottom: 1.5px solid var(--gray-200);
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky;
            top: 0; z-index: 900;
            box-shadow: var(--shadow-xs);
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; }
        .btn-toggle {
            display: none;
            width: 36px; height: 36px;
            border: 1.5px solid var(--gray-200);
            background: var(--gray-50);
            border-radius: var(--r-sm);
            align-items: center; justify-content: center;
            cursor: pointer;
            transition: all .18s;
        }
        .btn-toggle:hover { background: var(--blue-50); border-color: var(--blue-200); }
        .btn-toggle i { font-size: 18px; color: var(--gray-700); }
        .page-heading {
            font-size: 19px; font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -.02em;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            background: #fff;
            text-decoration: none;
            color: var(--gray-800);
            transition: .2s;
        }
        .profile-btn:hover { background: var(--gray-50); }

        .profile-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue-500), var(--peri-700));
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }

        .profile-info { line-height: 1.2; }
        .profile-info strong { display: block; font-size: 13px; }
        .profile-info span  { font-size: 11px; color: var(--gray-500); }

        /* CONTENT */
        .content-wrap {
            flex: 1;
            padding: 28px;
            background: #ffffff;
        }

        /* FLASH */
        .flash-stack {
            display: flex; flex-direction: column;
            gap: 10px; margin-bottom: 24px;
        }
        .flash {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 13px 16px;
            border-radius: var(--r-md);
            font-size: 13.5px; font-weight: 500;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .flash.success {
            background: var(--green-100);
            color: var(--green-600);
            border-left: 3px solid var(--green-600);
        }
        .flash.error {
            background: var(--rose-100);
            color: var(--rose-600);
            border-left: 3px solid var(--rose-600);
        }
        .flash i { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
        .flash .flash-close {
            margin-left: auto; background: none; border: none;
            color: inherit; opacity: .45; cursor: pointer;
            padding: 0; font-size: 16px; line-height: 1;
            transition: opacity .2s;
        }
        .flash .flash-close:hover { opacity: 1; }

        /* CARD */
        .card {
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--r-xl);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .card-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-100);
            padding: 18px 24px;
        }
        .card-header h5, .card-header h6 {
            font-weight: 700; color: var(--gray-900);
            margin: 0; letter-spacing: -.01em;
        }
        .card-body { padding: 24px; }

        /* TABLE */
        .table { margin: 0; font-size: 13.5px; }
        .table thead th {
            background: var(--gray-50);
            border: none;
            border-bottom: 1.5px solid var(--gray-200);
            padding: 11px 16px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase;
            color: var(--gray-400); white-space: nowrap;
        }
        .table tbody td {
            padding: 13px 16px;
            vertical-align: middle;
            border-color: var(--gray-100);
            color: var(--gray-700);
        }
        .table tbody tr { transition: background .15s; }
        .table tbody tr:hover td { background: var(--blue-50); }

        /* BADGE */
        .badge {
            padding: 4px 10px;
            font-size: 11px; font-weight: 600;
            border-radius: 999px; letter-spacing: .02em;
        }
        .badge-pending  { background: var(--peach-100); color: var(--peach-600); }
        .badge-approved { background: var(--green-100); color: var(--green-600); }
        .badge-rejected { background: var(--rose-100);  color: var(--rose-600);  }

        /* FORM */
        .form-label {
            font-size: 13px; font-weight: 600;
            color: var(--gray-700); margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--gray-200);
            border-radius: var(--r-md);
            padding: 10px 14px;
            font-size: 13.5px;
            font-family: 'DM Sans', sans-serif;
            color: var(--gray-900); background: white;
            transition: border-color .18s, box-shadow .18s;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(79,117,216,.15);
        }
        .form-control::placeholder { color: var(--gray-300); }

        /* BUTTONS */
        .btn {
            font-family: 'DM Sans', sans-serif;
            font-weight: 600; font-size: 13.5px;
            border-radius: var(--r-md);
            padding: 9px 18px; border: none;
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 7px;
            transition: all .18s;
            letter-spacing: -.01em;
        }
        .btn-primary { background: var(--blue-600); color: white; }
        .btn-primary:hover {
            background: var(--blue-700);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79,117,216,.38);
            color: white;
        }
        .btn-outline-primary {
            background: transparent;
            border: 1.5px solid var(--blue-500);
            color: var(--blue-700);
        }
        .btn-outline-primary:hover { background: var(--blue-50); color: var(--blue-700); }
        .btn-light { background: var(--gray-100); color: var(--gray-700); }
        .btn-light:hover { background: var(--gray-200); color: var(--gray-900); }
        .btn-secondary { background: var(--peri-100); color: var(--peri-700); border: none; }
        .btn-secondary:hover { background: var(--peri-200); color: var(--peri-700); }
        .btn-danger {
            background: var(--rose-100); color: var(--rose-600);
            border: 1.5px solid #f0b8c0;
        }
        .btn-danger:hover { background: var(--rose-600); color: white; border-color: var(--rose-600); }
        .btn-sm { padding: 6px 12px; font-size: 12.5px; }

        /* STAT CARD */
        .stat-card {
            background: white;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--r-lg);
            padding: 22px 24px;
            display: flex; align-items: center; gap: 18px;
            box-shadow: var(--shadow-xs);
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: var(--r-md);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon i { font-size: 22px; }
        .stat-icon.blue   { background: var(--blue-100);  color: var(--blue-700);  }
        .stat-icon.peri   { background: var(--peri-100);  color: var(--peri-700);  }
        .stat-icon.sky    { background: var(--sky-100);   color: #3a7fc1;          }
        .stat-icon.green  { background: var(--green-100); color: var(--green-600); }
        .stat-icon.peach  { background: var(--peach-100); color: var(--peach-600); }
        .stat-icon.rose   { background: var(--rose-100);  color: var(--rose-600);  }
        .stat-icon.teal   { background: var(--blue-100);  color: var(--blue-700);  }
        .stat-icon.amber  { background: var(--peach-100); color: var(--peach-600); }
        .stat-icon.red    { background: var(--rose-100);  color: var(--rose-600);  }
        .stat-num   { font-size: 26px; font-weight: 700; letter-spacing: -.03em; line-height: 1; color: var(--gray-900); }
        .stat-label { font-size: 12.5px; color: var(--gray-500); margin-top: 3px; }

        /* PAGINATION */
        .pagination { gap: 4px; margin: 0; }
        .page-link {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: var(--r-sm);
            font-size: 13px; font-weight: 500;
            border: 1.5px solid var(--gray-200);
            color: var(--gray-700);
            transition: all .18s;
        }
        .page-link:hover { background: var(--blue-50); border-color: var(--blue-200); color: var(--blue-700); }
        .page-item.active .page-link { background: var(--blue-600); border-color: var(--blue-600); color: white; }
        .page-item.disabled .page-link { opacity: .4; }

        /* OVERLAY */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(30,34,80,.32);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
            .sidebar-overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
            .btn-toggle { display: flex; }
            .content-wrap { padding: 18px 14px; }
            .topbar { padding: 0 16px; }
        }
        @media (max-width: 576px) {
            .page-heading { font-size: 16px; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- PAGE LOADER -->
<div id="page-loader">
    <div class="loader-ring"></div>
    <div class="loader-text">Memuat halaman…</div>
</div>

<!-- SIDEBAR OVERLAY -->
<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- LOGOUT MODAL -->
<div id="logout-modal" onclick="if(event.target===this) hideLogoutModal()">
    <div class="logout-modal-box">
        <div style="display:flex; align-items:center; gap:14px; margin-bottom:.25rem;">
            <div class="logout-modal-icon">
                <i class="bi bi-box-arrow-left"></i>
            </div>
            <div>
                <p class="logout-modal-title">Konfirmasi Logout</p>
                <p class="logout-modal-sub">Sistem Pengajuan Cuti & Izin</p>
            </div>
        </div>
        <p class="logout-modal-desc">
            Apakah Anda yakin ingin keluar? Sesi Anda akan diakhiri dan Anda perlu masuk kembali untuk melanjutkan.
        </p>
        <div class="logout-modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="hideLogoutModal()">
                <i class="bi bi-x-lg"></i> Batal
            </button>
            <button type="button" class="btn-modal-confirm" onclick="document.getElementById('logout-form').submit()">
                <i class="bi bi-box-arrow-left"></i> Ya, Logout
            </button>
        </div>
    </div>
</div>

<!-- FORM LOGOUT (tersembunyi) -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-inner">

        <!-- Brand -->
        <a class="sidebar-brand" href="{{ route('dashboard') }}">
            <div class="brand-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div class="brand-text">
                <strong>Sistem Pengajuan Cuti & Izin</strong>
            </div>
        </a>

        <div class="sidebar-nav-wrap">

            <div class="nav-label">Menu Utama</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        Dashboard
                    </a>
                </li>

                @if(auth()->user()->isGuru())
                <li>
                    <a href="{{ route('pengajuan.index') }}"
                       class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-plus-fill"></i>
                        Pengajuan Cuti
                    </a>
                </li>
                @endif

                @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('verifikasi.index') }}"
                        class="{{ request()->routeIs('verifikasi.*') ? 'active' : '' }}">
                        <i class="bi bi-patch-check-fill"></i>
                        Verifikasi Cuti
                    </a>
                </li>
                @endif

                @if(auth()->user()->isKepalaSekolah())
                <li>
                    <a href="{{ route('persetujuan.index') }}"
                       class="{{ request()->routeIs('persetujuan.*') ? 'active' : '' }}">
                        <i class="bi bi-check2-all"></i>
                        Persetujuan Cuti
                    </a>
                </li>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
                <li
                    class="nav-item">
                    {{-- Trigger: klik buka/tutup accordion --}}
                    <a class="nav-link d-flex align-items-center justify-content-between
                            {{ Request::is('laporan*') ? 'active' : '' }}"
                        data-bs-toggle="collapse"
                        href="#subLaporan"
                        role="button"
                        aria-expanded="{{ Request::is('laporan*') ? 'true' : 'false' }}"
                        aria-controls="subLaporan">

                        <span>
                            <i class="bi bi-bar-chart-line me-2"></i>Laporan
                        </span>
                        <i class="bi bi-chevron-down sub-arrow
                                {{ Request::is('laporan*') ? 'rotated' : '' }}"></i>
                    </a>

                    {{-- Sub-menu --}}
                    <div class="collapse {{ Request::is('laporan*') ? 'show' : '' }}" id="subLaporan">
                        <ul class="sub-menu list-unstyled ps-4 mb-0">

                            <li>
                                <a href="{{ route('laporan.pengajuan') }}"
                                class="sub-link {{ Request::routeIs('laporan.pengajuan') ? 'active' : '' }}">
                                    <i class="bi bi-clock-history me-2"></i>Histori Pengajuan
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('laporan.histori') }}"
                                class="sub-link {{ Request::routeIs('laporan.histori') ? 'active' : '' }}">
                                    <i class="bi bi-journal-check me-2"></i>Laporan Cuti
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('laporan.hak-cuti') }}"
                                class="sub-link {{ Request::routeIs('laporan.hak-cuti') ? 'active' : '' }}">
                                    <i class="bi bi-person-check me-2"></i>Sisa Hak Cuti Guru
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                @endif
            </ul>

            @if(auth()->user()->isAdmin())
            <div class="nav-label" style="margin-top:6px;">Akun</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('admin.guru.index') }}"
                    class="{{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        Manajemen User
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.data-guru.index') }}"
                    class="{{ request()->routeIs('admin.data-guru.*') ? 'active' : '' }}">
                        <i class="bi bi-person-lines-fill"></i>
                        Data Guru
                    </a>
                </li>
            </ul>
            @endif

    </div>

        <!-- Logout hanya di sini -->
        <div class="sidebar-footer">
            <button type="button" class="btn-logout" onclick="showLogoutModal()">
                <i class="bi bi-box-arrow-left"></i>
                Logout
            </button>
        </div>

    </div>
</aside>

<!-- MAIN -->
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn-toggle" id="btn-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                @hasSection('welcome-title')
                    @yield('welcome-subtitle')
                    <h1 class="page-heading">@yield('welcome-title')</h1>
                @else
                    <h1 class="page-heading">@yield('page-title')</h1>
                @endif
            </div>
        </div>
        <div class="topbar-right">
            @hasSection('welcome-badge')
            <span style="
                background:#2d3a8c;
                border-radius:10px;
                padding:7px 14px;
                color:#fff;
                font-size:12px;
                font-weight:500;
            ">
                @yield('welcome-badge')
            </span>
            @endif

            {{-- // ✅ Langsung ke profil, tanpa dropdown //  --}}
            <a href="{{ route('profile.show') }}" class="profile-btn">
                <div class="profile-avatar">
                    {{ collect(explode(' ', auth()->user()->nama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') }}
                </div>
                <div class="profile-info">
                    <strong>{{ auth()->user()->nama }}</strong>
                    <span>{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </a>
        </div>
    </header>

    <main class="content-wrap">

        @if(session('success') || session('error'))
        <div class="flash-stack">
            @if(session('success'))
            <div class="flash success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button class="flash-close" onclick="this.closest('.flash').remove()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div class="flash error">
                <i class="bi bi-exclamation-circle-fill"></i>
                <span>{{ session('error') }}</span>
                <button class="flash-close" onclick="this.closest('.flash').remove()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            @endif
        </div>
        @endif

        @yield('content')

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sembunyikan loader saat halaman selesai dimuat
    window.addEventListener('load', function () {
        document.getElementById('page-loader').style.display = 'none';
    });

    // Sembunyikan loader saat user tekan back/forward browser
    window.addEventListener('pageshow', function () {
        document.getElementById('page-loader').style.display = 'none';
    });

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebar-overlay').classList.toggle('open');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('open');
    }

    function showLogoutModal() {
        document.getElementById('logout-modal').style.display = 'flex';
    }

    function hideLogoutModal() {
        document.getElementById('logout-modal').style.display = 'none';
    }

    // Tampilkan loader hanya untuk link navigasi biasa
    // Kecuali: accordion collapse (href="#..."), data-bs-toggle, javascript, mailto
    document.querySelectorAll('a[href]').forEach(function(link) {
        link.addEventListener('click', function() {
            const href = this.getAttribute('href');
            const hasBsToggle = this.hasAttribute('data-bs-toggle');
            const isCollapse = href && href.startsWith('#');
            if (
                href &&
                !isCollapse &&
                !hasBsToggle &&
                !href.startsWith('javascript') &&
                !href.startsWith('mailto') &&
                !this.hasAttribute('target')
            ) {
                document.getElementById('page-loader').style.display = 'flex';
            }
        });
    });

    setTimeout(function() {
        document.querySelectorAll('.flash').forEach(function(el) {
            el.style.transition = 'opacity .4s, transform .4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            setTimeout(() => el.remove(), 400);
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
