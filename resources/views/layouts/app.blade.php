<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - SIGAP Bawaslu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark: #1f2430; --dark-hover: #2b3140; --light: #f3f5f8;
            --text: #1f2430; --muted: #667085; --border: #e5e8ee; --red: #b01f2a;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "IBM Plex Sans", system-ui, sans-serif; background: var(--light); color: var(--text); }

        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 260px;
            background: var(--dark); color: #e8ebf2; padding: 32px 22px;
            display: flex; flex-direction: column; z-index: 20; overflow-y: auto;
        }
        .sidebar h1 { margin: 0; font-size: 28px; letter-spacing: 1.5px; }
        .sidebar .tag { margin: 4px 0 28px; color: #aab2c3; font-size: 13px; }
        .sidebar nav { flex: 1; }
        .sidebar nav a {
            display: block; padding: 12px 16px; margin-bottom: 4px;
            color: #dfe3ec; text-decoration: none; border-radius: 6px;
            border-left: 3px solid transparent; font-size: 15px;
        }
        .sidebar nav a:hover { background: var(--dark-hover); }
        .sidebar nav a.active { background: var(--dark-hover); border-left-color: var(--red); color: #fff; font-weight: 600; }
        .sidebar .me { border-top: 1px solid #343b4c; padding-top: 18px; margin-top: 16px; }
        .sidebar .me strong { display: block; font-size: 16px; }
        .sidebar .me span { color: #aab2c3; font-size: 13px; }
        .sidebar .me button {
            width: 100%; margin-top: 14px; padding: 11px; background: transparent;
            color: #fff; border: 1px solid #4a5265; border-radius: 6px;
            font-family: inherit; font-size: 14px; cursor: pointer;
        }
        .sidebar .me button:hover { background: var(--dark-hover); }

        .main { margin-left: 260px; padding: 40px 48px; }
        .main h2.page { margin: 0 0 6px; font-size: 28px; }
        .main p.sub { margin: 0 0 24px; color: var(--muted); }

        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
        .stat { background: #fff; border: 1px solid var(--border); border-radius: 10px; padding: 22px 24px; }
        .stat .n { font-size: 36px; font-weight: 600; }
        .stat .n.merah { color: var(--red); }
        .stat .l { color: var(--muted); font-size: 15px; margin-top: 4px; }

        .panel { background: #fff; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
        .panel + .panel { margin-top: 20px; }
        .panel h3 { margin: 0; padding: 20px 24px; font-size: 17px; border-bottom: 1px solid var(--border); }
        .scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 14px 24px; color: var(--muted); font-weight: 500; font-size: 14px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        td { padding: 14px 24px; border-bottom: 1px solid var(--border); font-size: 15px; vertical-align: top; }
        tr:last-child td { border-bottom: 0; }
        .empty { padding: 28px 24px; color: var(--muted); }

        .muted { color: var(--muted); font-size: 13px; }
        .link { color: var(--red); text-decoration: none; font-weight: 500; }
        .link:hover { text-decoration: underline; }

        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; white-space: nowrap; }
        .b-wait { background: #fdf0d5; color: #92600a; }
        .b-ok   { background: #e3f4ea; color: #1f7a45; }
        .b-rev  { background: #fbe4e6; color: #b01f2a; }
        .b-info { background: #e1ecfb; color: #1d4f91; }

        .btn { display: inline-block; padding: 11px 18px; border: 0; border-radius: 6px; background: var(--red); color: #fff; font-family: inherit; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #8f1922; }
        .btn-sm { padding: 7px 12px; font-size: 13px; }
        .btn-dark { background: var(--dark); }
        .btn-dark:hover { background: var(--dark-hover); }
        .toolbar { margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; }

        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert.ok { background: #e3f4ea; color: #1f7a45; }
        .alert.err { background: #fbe4e6; color: #9b1c1c; }
        .alert.warn { background: #fdf0d5; color: #92600a; }
        .alert ul { margin: 0; padding-left: 18px; }
        .alert a { color: inherit; font-weight: 600; }

        .info { padding: 20px 24px 24px; }
        .info dl { display: grid; grid-template-columns: 190px 1fr; gap: 14px 12px; margin: 0; }
        .info dt { color: var(--muted); }
        .info dd { margin: 0; font-weight: 500; }
        .info p { margin: 14px 0 0; }

        .form { padding: 8px 24px 24px; }
        .form label { display: block; margin: 16px 0 6px; font-weight: 600; font-size: 14px; }
        .form input[type=text], .form input[type=number], .form input[type=datetime-local],
        .form input[type=file], .form select, .form textarea {
            width: 100%; padding: 11px 12px; border: 1px solid #d5deeb; border-radius: 6px;
            background: #f7f9fc; font-family: inherit; font-size: 15px; color: var(--text);
        }
        .form textarea { min-height: 90px; resize: vertical; }
        .form .check { display: flex; gap: 10px; align-items: center; font-weight: 500; }
        .form .check input { width: auto; }
        .form .actions { display: flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
        .nilai-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 0 16px; }

        .fotos { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; padding: 20px 24px 24px; }
        .fotos img { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }

        .timeline { list-style: none; margin: 0; padding: 22px 24px 8px 30px; }
        .timeline li { position: relative; padding: 0 0 18px 22px; border-left: 2px solid var(--border); }
        .timeline li::before { content: ""; position: absolute; left: -7px; top: 2px; width: 12px; height: 12px; border-radius: 50%; background: #9aa6b8; }
        .timeline li.t-disetujui::before { background: #1f7a45; }
        .timeline li.t-revisi::before { background: var(--red); }
        .timeline li.t-dikirim::before, .timeline li.t-dikirim_ulang::before { background: #c98a1b; }
        .timeline li p { margin: 6px 0 0; font-size: 14px; }
        .timeline li .muted { display: block; }

        .topbar { display: none; }
        @media (max-width: 860px) {
            .topbar { display: flex; align-items: center; gap: 14px; position: sticky; top: 0; z-index: 10; background: var(--dark); color: #fff; padding: 14px 18px; }
            .topbar button { background: none; border: 0; color: #fff; font-size: 24px; cursor: pointer; }
            .sidebar { transform: translateX(-100%); transition: transform .2s; }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; padding: 24px 18px; }
            .stats { grid-template-columns: 1fr; }
            .info dl { grid-template-columns: 1fr; gap: 4px; }
            .info dd { margin-bottom: 10px; }
        }
    </style>
</head>
<body>
@php
    $isAdmin = auth()->user()->isAdmin();

    $menu = $isAdmin ? [
        ['Dashboard',              route('dashboard'),                           request()->is('/')],
        ['Semua Tugas',            route('admin.tugas'),                         request()->is('admin/tugas') || (request()->is('admin/tugas/*') && ! request()->is('admin/tugas/jabatan/*', 'admin/tugas/buat'))],
        ['Tugas Satpam',           route('admin.tugas.jabatan', 'satpam'),       request()->is('admin/tugas/jabatan/satpam')],
        ['Tugas Pramubakti (OB)',  route('admin.tugas.jabatan', 'pramubakti'),   request()->is('admin/tugas/jabatan/pramubakti')],
        ['Buat Tugas',             route('admin.tugas.buat'),                    request()->is('admin/tugas/buat')],
        ['Verifikasi & Penilaian', route('admin.verifikasi'),                    request()->is('admin/verifikasi', 'admin/laporan/*')],
        ['Semua Akun',             route('admin.akun'),                          request()->is('admin/akun', 'admin/akun/*')],
    ] : [
        ['Dashboard',        route('dashboard'),       request()->is('/')],
        ['Tugas Tersedia',   route('petugas.tugas'),   request()->is('tugas')],
        ['Laporan Saya',     route('petugas.laporan'), request()->is('laporan', 'laporan/*')],
        ['Pengaturan Akun',  route('akun.saya'),       request()->is('pengaturan')],
    ];
@endphp

<div class="topbar">
    <button type="button" onclick="document.getElementById('sidebar').classList.toggle('open')">&#9776;</button>
    <strong>SIGAP</strong>
</div>

<aside class="sidebar" id="sidebar">
    <h1>SIGAP</h1>
    <p class="tag">Bawaslu - Kinerja Harian</p>

    <nav>
        @foreach ($menu as [$label, $url, $aktif])
            <a href="{{ $url }}" class="{{ $aktif ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </nav>

    <div class="me">
        <strong>{{ auth()->user()->name }}</strong>
        <span>{{ $isAdmin ? 'Admin' : auth()->user()->labelJabatan() }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </div>
</aside>

<main class="main">
    @if (session('ok'))
        <div class="alert ok">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert err">
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

</body>
</html>