@php($isAdmin = $isAdmin ?? (auth()->user()?->role === 'admin'))
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - SIGAP Bawaslu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1E2530;
            --paper: #F4F5F7;
            --surface: #FFFFFF;
            --line: #DDE1E7;
            --muted: #5B6574;
            --red: #B3202A;
            --red-dark: #8E1821;
            --ok: #1F7A4D;
            --ok-bg: #E4F3EB;
            --warn: #8A5200;
            --warn-bg: #FBEFD9;
            --bad: #B3202A;
            --bad-bg: #FBE4E5;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'IBM Plex Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            background: var(--paper);
            color: var(--ink);
            line-height: 1.5;
        }
        a { color: inherit; text-decoration: none; }
        :focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }

        .shell { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            background: var(--ink);
            color: #E8EBF0;
            padding: 28px 18px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .logo { font-size: 26px; font-weight: 600; letter-spacing: .04em; padding: 0 10px; }
        .logo-sub { font-size: 13px; color: #A9B1BE; padding: 0 10px; margin-top: 2px; }
        .menu { margin-top: 32px; display: flex; flex-direction: column; gap: 2px; }
        .menu a {
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 15px;
            color: #C9CFD9;
            border-left: 3px solid transparent;
        }
        .menu a:hover { background: rgba(255,255,255,.06); color: #fff; }
        .menu a.aktif { background: rgba(255,255,255,.08); color: #fff; border-left-color: var(--red); }
        .menu .pemisah { height: 1px; background: rgba(255,255,255,.1); margin: 10px 0; }

        .akun { margin-top: auto; padding: 14px 10px 0; border-top: 1px solid rgba(255,255,255,.1); }
        .akun-nama { font-weight: 500; }
        .akun-peran { font-size: 13px; color: #A9B1BE; }
        .akun form { margin-top: 12px; }
        .btn-keluar {
            width: 100%;
            padding: 9px 12px;
            background: transparent;
            color: #E8EBF0;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 6px;
            font: inherit;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-keluar:hover { background: rgba(255,255,255,.08); }

        /* Konten */
        .konten { padding: 36px 40px; max-width: 1100px; width: 100%; }
        .konten h1 { font-size: 26px; font-weight: 600; margin: 0 0 4px; }
        .konten .sub { color: var(--muted); margin: 0 0 28px; }

        .panel { background: var(--surface); border: 1px solid var(--line); border-radius: 8px; }
        .panel-judul { padding: 16px 20px; border-bottom: 1px solid var(--line); font-weight: 600; }
        .panel-isi { padding: 20px; }

        .ringkasan { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .ringkasan .panel { padding: 18px 20px; }
        .ringkasan .angka { font-size: 32px; font-weight: 600; line-height: 1.1; }
        .ringkasan .label { color: var(--muted); font-size: 14px; margin-top: 4px; }
        .ringkasan .perhatian .angka { color: var(--red); }

        table { width: 100%; border-collapse: collapse; font-size: 15px; }
        th, td { text-align: left; padding: 12px 20px; border-bottom: 1px solid var(--line); }
        th { font-weight: 500; color: var(--muted); font-size: 14px; }
        tr:last-child td { border-bottom: 0; }
        .tabel-wrap { overflow-x: auto; }

        .badge { display: inline-block; padding: 2px 10px; border-radius: 99px; font-size: 13px; font-weight: 500; }
        .badge.ok   { background: var(--ok-bg);   color: var(--ok); }
        .badge.warn { background: var(--warn-bg); color: var(--warn); }
        .badge.bad  { background: var(--bad-bg);  color: var(--bad); }

        .tombol {
            display: inline-block;
            padding: 10px 18px;
            background: var(--red);
            color: #fff;
            border-radius: 6px;
            font-weight: 500;
        }
        .tombol:hover { background: var(--red-dark); }

        @media (max-width: 860px) {
            .shell { grid-template-columns: 1fr; }
            .sidebar { position: static; height: auto; padding: 18px; }
            .menu { flex-direction: row; flex-wrap: wrap; margin-top: 16px; }
            .menu .pemisah { display: none; }
            .akun { margin-top: 16px; }
            .konten { padding: 24px 18px; }
            .ringkasan { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <div class="logo">SIGAP</div>
        <div class="logo-sub">Bawaslu - Kinerja Harian</div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'aktif' : '' }}">Dashboard</a>

            @if ($isAdmin)
                <a href="#">Semua Laporan</a>
                <a href="#">Data Tenaga Pendukung</a>
                <a href="#">Verifikasi Laporan</a>
                <a href="#">Evaluasi</a>
                <a href="#">Kelola Akun</a>
            @else
                <a href="#">Upload Laporan Harian</a>
                <a href="#">Laporan Saya</a>
                <a href="#">Revisi dari Atasan</a>
                <a href="#">Pengaturan Akun</a>
            @endif
        </nav>

        <div class="akun">
            <div class="akun-nama">{{ auth()->user()->name }}</div>
            <div class="akun-peran">{{ $isAdmin ? 'Admin' : 'Tenaga Pendukung' }}</div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-keluar">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="konten">
        @yield('content')
    </main>
</div>
</body>
</html>
