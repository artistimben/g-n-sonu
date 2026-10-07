<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş yap · Boz Tech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-layout">
        <section class="auth-visual">
            <a class="auth-brand" href="{{ route('login') }}"><span class="brand-mark">b<span>.</span></span><span><strong>boz tech</strong><small>çalışma alanı</small></span></a>
            <div class="auth-art-lines"></div><div class="auth-art-disc"></div><div class="auth-art-card"><span>STUDIO<br>DESK</span><b>BT.</b></div>
            <div class="auth-visual-copy"><p>Üretimin ritmini<br><strong>birlikte tutuyoruz.</strong></p><span>Raporlar, çalışma saatleri ve ekip.</span></div>
            <div class="auth-visual-footer"><span>boz tech bilişim</span><span>01 / 03 — ÇALIŞMA ALANI</span></div>
        </section>
        <section class="auth-main">
            <div class="auth-form-wrap">
                <p class="eyebrow">BOZ TECH EKİBİ</p>
                <h1>Tekrar hoş geldin.</h1>
                <p class="auth-intro">Çalışma alanına erişmek için bilgilerini gir.</p>

                @if(session('success'))<div class="auth-message">{{ session('success') }}</div>@endif
                @if($errors->any())<div class="auth-error" role="alert">{{ $errors->first() }}</div>@endif

                <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                    @csrf
                    <label for="email">E-posta adresi</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="ad@boztech.com" required autofocus>
                    <div class="field-label-row"><label for="password">Şifre</label></div>
                    <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Şifren" required>
                    <label class="check-row"><input type="checkbox" name="remember" value="1"><span>Beni bu cihazda hatırla</span></label>
                    <button class="button button-dark button-wide auth-submit" type="submit">Çalışma alanına gir <span>↗</span></button>
                </form>
                @if(!\App\Models\User::exists())
                    <div class="auth-setup-note">İlk yönetici misin? <a href="{{ route('setup') }}">Kurulumunu başlat</a></div>
                @endif
                <p class="auth-help">Hesabına erişemiyor musun? Yöneticinden yeni şifre iste.</p>
            </div>
            <div class="auth-mobile-footer">boz tech bilişim · çalışma alanı</div>
        </section>
    </main>
</body>
</html>
