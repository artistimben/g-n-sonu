@extends('layouts.app')

@section('title', 'Ekip yönetimi')

@section('content')
    <section class="page-heading">
        <div><p class="eyebrow">YÖNETİCİ ALANI</p><h1>Ekibinle aynı masada.</h1><p class="heading-subtitle">Boz Tech çalışma alanına ekip üyeleri ekle ve rapor akışını takip et.</p></div>
    </section>

    <div class="employee-layout">
        <section class="panel employee-list-panel">
            <div class="panel-heading"><div><p class="eyebrow">{{ $employees->total() }} ÜYE</p><h2>Çalışma alanı erişimi</h2></div><span class="employee-count">{{ $employees->total() }}</span></div>
            <div class="employee-list">
                @forelse($employees as $employee)
                    <div class="employee-row"><span class="avatar">{{ mb_strtoupper(mb_substr($employee->name, 0, 1)) }}</span><div class="employee-info"><strong>{{ $employee->name }}</strong><span>{{ $employee->email }}</span></div><span class="employee-role">Ekip üyesi</span><span class="employee-joined">{{ $employee->created_at->format('d.m.Y') }}</span></div>
                @empty
                    <div class="employee-empty"><strong>Ekibin henüz burada değil.</strong><span>İlk ekip arkadaşının hesabını sağdaki formdan oluştur.</span></div>
                @endforelse
            </div>
            @if($employees->hasPages())<div class="employee-pagination">{{ $employees->links() }}</div>@endif
        </section>

        <aside class="panel employee-form-panel">
            <div class="panel-heading"><div><p class="eyebrow">YENİ HESAP</p><h2>Ekip üyesi ekle</h2></div><span class="add-member-mark">+</span></div>
            <p class="employee-form-intro">Hesap bilgilerini çalışanla paylaş. Bu bilgilerle giriş yapıp mesaisini ve raporlarını kaydedebilir.</p>
            <form method="POST" action="{{ route('employees.store') }}" class="employee-form">
                @csrf
                <label for="name">Ad soyad</label><input id="name" name="name" value="{{ old('name') }}" placeholder="Ad Soyad" required>
                <label for="email">E-posta</label><input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="ad@boztech.com" required>
                <label for="password">Başlangıç şifresi</label><input id="password" type="password" name="password" autocomplete="new-password" placeholder="En az 10 karakter" required><small>En az 10 karakter, en az bir harf ve rakam.</small>
                <label for="password_confirmation">Şifre tekrarı</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Şifreyi tekrar yaz" required>
                <button class="button button-blue button-wide" type="submit">Çalışan hesabı oluştur <span>↗</span></button>
            </form>
            <div class="employee-permission-note"><span>i</span><p>Çalışanlar yalnızca kendi mesai ve rapor kayıtlarını görür. Yönetici, ekip raporlarının tamamına erişebilir.</p></div>
        </aside>
    </div>
@endsection
