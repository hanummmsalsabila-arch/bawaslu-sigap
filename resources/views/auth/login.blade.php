<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIGAP Bawaslu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #1E2530;
            --paper: #F4F5F7;
            --surface: #FFFFFF;
            --line: #C9CFD9;
            --muted: #5B6574;
            --red: #B3202A;
            --red-dark: #8E1821;
            --bad-bg: #FBE4E5;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'IBM Plex Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            color: var(--ink);
            background: var(--paper);
            line-height: 1.5;
        }
        :focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }

        .halaman { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; }

        .sisi-merek {
            background: var(--ink);
            color: #E8EBF0;
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-left: 6px solid var(--red);
        }
        .merek { font-size: 44px; font-weight: 600; letter-spacing: .04em; line-height: 1; }
        .merek-sub { color: #A9B1BE; margin-top: 8px; }
        .tagline { font-size: 22px; font-weight: 500; max-width: 24ch; line-height: 1.35; }
        .catatan { color: #A9B1BE; font-size: 14px; }

        .sisi-form { display: flex; align-items: center; justify-content: center; padding: 40px 24px; }
        .kartu { width: 100%; max-width: 380px; }
        .kartu h1 { font-size: 26px; font-weight: 600; margin: 0 0 6px; }
        .kartu .sub { color: var(--muted); margin: 0 0 28px; }

        .galat {
            background: var(--bad-bg);
            color: var(--red-dark);
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .galat ul { margin: 0; padding-left: 18px; }

        .baris { margin-bottom: 18px; }
        label { display: block; font-weight: 500; margin-bottom: 6px; }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 11px 12px;
            font: inherit;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: var(--surface);
        }
        input[type="email"]:focus, input[type="password"]:focus { border-color: var(--red); }

        .ingat { display: flex; align-items: center; gap: 8px; font-weight: 400; margin: 0; cursor: pointer; }
        .ingat input { width: 16px; height: 16px; accent-color: var(--red); }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            background: var(--red);
            color: #fff;
            font: inherit;
            font-weight: 500;
            border: 0;
            border-radius: 6px;
            cursor: pointer;
        }
        button[type="submit"]:hover { background: var(--red-dark); }

        @media (max-width: 860px) {
            .halaman { grid-template-columns: 1fr; }
            .sisi-merek { padding: 28px 24px; gap: 20px; }
            .merek { font-size: 32px; }
            .tagline { font-size: 18px; }
            .catatan { display: none; }
        }
    </style>
</head>
<body>
<div class="halaman">
    <section class="sisi-merek">
        <div>
            <div class="merek">SIGAP</div>
            <div class="merek-sub">Bawaslu - Kinerja Harian</div>
        </div>
        <div class="tagline">Laporan harian tenaga pendukung, dari upload sampai evaluasi.</div>
        <div class="catatan">Gunakan akun yang diberikan oleh admin.</div>
    </section>

    <section class="sisi-form">
        <div class="kartu">
            <h1>Masuk ke SIGAP</h1>
            <p class="sub">Masukkan email dan password Anda.</p>

            @if ($errors->any())
                <div class="galat" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="baris">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                </div>

                <div class="baris">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                </div>

                <div class="baris">
                    <label class="ingat">
                        <input type="checkbox" name="ingat" value="1" {{ old('ingat') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>

                <button type="submit">Masuk</button>
            </form>
        </div>
    </section>
</div>
</body>
</html>