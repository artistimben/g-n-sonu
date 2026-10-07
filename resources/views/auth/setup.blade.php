<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>İlk kurulum · Boz Tech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-page">
    <main class="auth-layout">
        <section class="auth-visual">
            <a class="auth-brand" href="{{ route('setup') }}"><span class="brand-mark">b<span>.</span></span><span><strong>boz tech</strong><small>çalışma alanı</small></span></a>
            <div class="auth-art-lines"></div><div class="auth-art-disc"></div><div class="auth-art-card"><span>STUDIO<br>DESK</span><b>BT.</b></div>
            <div class="auth-visual-copy"><p>Ekip için yeni bir<br><strong>başlangıç noktası.</strong></p><span>Önce yönetici hesabını oluşturalım.</span></div>
            <div class="auth-visual-footer"><span>boz tech bilişim</span><span>01 / 03 — KURULUM</span></div>
        </section>
        <section class="auth-main">
            <div class="auth-form-wrap">
                <p class="eyebrow">İLK KURULUM</p>
                <h1>Yönetici hesabı oluştur.</h1>
                <p class="auth-intro">Kurulum yalnızca ilk hesap için açıktır. Sonrasında ekip üyelerini yönetici olarak ekleyebilirsin.</p>
                @if($errors->any())<div class="auth-error" role="alert">{{ $errors->first() }}</div>@endif
                <form method="POST" action="{{ route('setup.store') }}" class="auth-form">
                    @csrf
                    <label for="name">Ad soyad</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Ad Soyad" required autofocus>
                    <label for="email">E-posta adresi</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="ad@boztech.com" required>
                    <label for="password">Şifre</label><input id="password" type="password" name="password" autocomplete="new-password" placeholder="En az 10 karakter, harf ve rakam" required>
                    <label for="password_confirmation">Şifre tekrarı</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Şifreni tekrar yaz" required>
                    <button class="button button-dark button-wide auth-submit" type="submit">Yönetici hesabını oluştur <span>↗</span></button>
                </form>
                <div class="auth-setup-note"><a href="{{ route('login') }}">Giriş sayfasına dön</a></div>
            </div>
            <div class="auth-mobile-footer">boz tech bilişim · çalışma alanı</div>
        </section>
    </main>
</body>
</html>
