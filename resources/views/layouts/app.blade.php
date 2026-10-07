<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Çalışma alanı') · Boz Tech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell">
    @php($routeName = request()->route()?->getName())
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}" aria-label="Boz Tech ana sayfa">
            <span class="brand-mark">b<span>.</span></span>
            <span class="brand-copy"><strong>boz tech</strong><small>çalışma alanı</small></span>
        </a>

        <div class="nav-caption">Çalışma alanı</div>
        <nav class="side-nav" aria-label="Ana menü">
            <a class="nav-link {{ $routeName === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="4" rx="1.5"/><rect x="13.5" y="10.5" width="7" height="10" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
                <span>Genel bakış</span>
            </a>
            <a class="nav-link {{ str_starts_with($routeName ?? '', 'reports.') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.75h7l4.25 4.5v12H7a2 2 0 0 1-2-2v-12a2.5 2.5 0 0 1 2-2.5Z"/><path d="M14 4v5h4M8.5 13h7M8.5 16.5h7"/></svg>
                <span>Raporlar</span>
            </a>
            @if(auth()->user()->isAdmin())
                <a class="nav-link {{ str_starts_with($routeName ?? '', 'employees.') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3.25"/><path d="M3.75 19.25a5.25 5.25 0 0 1 10.5 0M16.25 5.25a3 3 0 0 1 0 5.75M17.25 14.5a4.5 4.5 0 0 1 3 4.25"/></svg>
                    <span>Ekip</span>
                </a>
            @endif
        </nav>

        <div class="sidebar-note">
            <span class="sidebar-note-dot"></span>
            <div><strong>Günün ritmi</strong><span>Kaydını tut, odağını koru.</span></div>
        </div>

        <div class="sidebar-bottom">
            <div class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="profile-copy"><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->isAdmin() ? 'Yönetici' : 'Ekip üyesi' }}</span></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="icon-button logout-button" type="submit" aria-label="Çıkış yap" title="Çıkış yap">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4.5H5.5v15H10M14 8l4 4-4 4M8.5 12H18"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-column">
        <header class="topbar">
            <div class="mobile-brand"><span class="brand-mark">b<span>.</span></span><strong>boz tech</strong></div>
            <div class="topbar-date"><span class="online-indicator"></span>{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="topbar-right"><span>Türkiye saati</span><span class="topbar-divider"></span><span>{{ auth()->user()->isAdmin() ? 'Yönetici hesabı' : 'Ekip hesabı' }}</span></div>
            <form method="POST" action="{{ route('logout') }}" class="mobile-logout-form">@csrf<button class="mobile-logout" type="submit" aria-label="Çıkış yap"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4.5H5.5v15H10M14 8l4 4-4 4M8.5 12H18"/></svg></button></form>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="flash flash-success" role="status"><span class="flash-symbol">✓</span>{{ session('success') }}<button type="button" class="flash-close" aria-label="Bildirimi kapat">×</button></div>
            @endif
            @if($errors->any())
                <div class="flash flash-error" role="alert"><span class="flash-symbol">!</span><div><strong>İşlem tamamlanamadı.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div><button type="button" class="flash-close" aria-label="Bildirimi kapat">×</button></div>
            @endif
            @yield('content')
        </main>
        <footer class="page-footer"><span>boz tech bilişim</span><span>Üretim görünür olduğunda, ekip daha güçlü çalışır.</span></footer>
    </div>
</body>
</html>
