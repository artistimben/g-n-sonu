@extends('layouts.app')

@section('title', 'Genel bakış')

@section('content')
    <section class="page-heading dashboard-heading">
        <div>
            <p class="eyebrow">{{ $today->translatedFormat('l, d F') }}</p>
            <h1>Merhaba, {{ explode(' ', $user->name)[0] }}.</h1>
            <p class="heading-subtitle">Günün üretimini ve çalışma saatlerini tek yerde tut.</p>
        </div>
        <a class="button button-dark" href="{{ route('reports.create', ['type' => 'daily', 'date' => $today->toDateString()]) }}">
            <span class="button-plus">+</span> Gün sonu raporu yaz
        </a>
    </section>

    <section class="hero-panel">
        <div class="hero-copy">
            <div class="hero-label"><span class="hero-label-dot"></span> BOZ TECH · STÜDYO MASASI</div>
            <h2>İyi işler,<br><span>düzenli iz bırakır.</span></h2>
            <p>Mesaini kaydet. Ürettiklerini paylaş. Ekibin aynı ritimde kalsın.</p>
            <a href="{{ route('reports.create', ['type' => 'daily', 'date' => $today->toDateString()]) }}" class="hero-link">Bugünün raporuna başla <span>↗</span></a>
        </div>
        <div class="hero-art" aria-hidden="true">
            <div class="art-grid"></div>
            <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
            <div class="art-slice"></div><div class="art-square"></div>
            <div class="art-stamp">B<br><span>T</span></div>
            <div class="art-caption">IDEAS<br>IN MOTION</div>
        </div>
    </section>

    <section class="metrics-grid" aria-label="Haftalık özet">
        <article class="metric-card">
            <div class="metric-top"><span class="metric-label">Bu hafta çalışılan</span><span class="metric-icon blue">◷</span></div>
            <div class="metric-value">{{ intdiv($weekMinutes, 60) }}<small>s</small> {{ str_pad((string) ($weekMinutes % 60), 2, '0', STR_PAD_LEFT) }}<small>dk</small></div>
            <div class="metric-note">{{ $weekStart->format('d.m') }} – {{ $weekEnd->format('d.m') }} arası</div>
        </article>
        <article class="metric-card">
            <div class="metric-top"><span class="metric-label">Bugünün raporu</span><span class="metric-icon {{ $todayReport ? 'green' : 'amber' }}">{{ $todayReport ? '✓' : '✎' }}</span></div>
            <div class="metric-value metric-text">{{ $todayReport ? $todayReport->status_label : 'Henüz yazılmadı' }}</div>
            <div class="metric-note">{{ $todayReport ? 'Son güncelleme ' . $todayReport->updated_at->format('H:i') : 'Gün bitmeden notlarını ekle' }}</div>
        </article>
        <article class="metric-card">
            <div class="metric-top"><span class="metric-label">Haftalık rapor</span><span class="metric-icon violet">▤</span></div>
            <div class="metric-value metric-text">{{ $weeklySubmitted ? 'Tamamlandı' : 'Sırada' }}</div>
            <div class="metric-note">{{ $weeklySubmitted ? 'Bu haftanın özeti hazır' : 'Haftanı kısa bir özetle kapat' }}</div>
        </article>
    </section>

    @if($user->isAdmin())
        <section class="team-overview">
            <div class="section-heading">
                <div><p class="eyebrow">YÖNETİCİ GÖRÜNÜMÜ</p><h2>Ekip bugün nasıl?</h2></div>
                <a class="text-link" href="{{ route('employees.index') }}">Ekibi yönet <span>↗</span></a>
            </div>
            <div class="team-stats">
                <div><strong>{{ $teamStats['employees'] }}</strong><span>ekip üyesi</span></div>
                <div><strong>{{ $teamStats['clocked_in'] }}</strong><span>şu an mesaiye devam ediyor</span></div>
                <div><strong>{{ $teamStats['reports_today'] }}</strong><span>gün sonu raporu tamamlandı</span></div>
                <div><strong>{{ $teamStats['reports_week'] }}</strong><span>haftalık rapor tamamlandı</span></div>
            </div>
            <div class="team-table-wrap">
                <table class="team-table">
                    <thead><tr><th>Çalışan</th><th>Giriş saati</th><th>Çıkış saati</th><th>Bugünkü rapor</th><th>Durum</th></tr></thead>
                    <tbody>
                    @forelse($team as $member)
                        @php($attendance = $member->attendances->first())
                        @php($memberReport = $member->reports()->where('type', 'daily')->whereDate('period_start', $today)->first())
                        <tr>
                            <td><div class="person-cell"><span class="avatar avatar-small">{{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}</span><span>{{ $member->name }}</span></div></td>
                            <td>{{ $attendance?->clock_in_at?->format('H:i') ?? '—' }}</td>
                            <td>{{ $attendance?->clock_out_at?->format('H:i') ?? '—' }}</td>
                            <td>{{ $memberReport?->status_label ?? 'Bekliyor' }}</td>
                            <td><span class="status-pill {{ $attendance && ! $attendance->clock_out_at ? 'status-live' : ($attendance?->clock_out_at ? 'status-done' : 'status-idle') }}"><i></i>{{ $attendance && ! $attendance->clock_out_at ? 'Mesai devam ediyor' : ($attendance?->clock_out_at ? 'Mesai tamamlandı' : 'Giriş yapılmadı') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">Ekibiniz boş görünüyor. İlk çalışanı eklemek için <a href="{{ route('employees.index') }}">buraya gidin.</a></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <div class="dashboard-columns">
        <section class="panel attendance-panel">
            <div class="panel-heading">
                <div><p class="eyebrow">BUGÜN · {{ $today->format('d.m.Y') }}</p><h2>Mesai kaydı</h2></div>
                <span class="live-badge"><i></i> CANLI</span>
            </div>
            <div class="clock-display" id="live-clock" data-now="{{ now()->timestamp }}">{{ now()->format('H:i:s') }}</div>
            <p class="clock-caption">Türkiye saati · giriş ve çıkış anları otomatik kaydedilir</p>
            <div class="attendance-line">
                <div class="attendance-point {{ $todayAttendance?->clock_in_at ? 'point-done' : '' }}"><span class="point-mark">{{ $todayAttendance?->clock_in_at ? '✓' : '1' }}</span><div><strong>Mesai girişi</strong><small>{{ $todayAttendance?->clock_in_at?->format('H:i') ?? 'Henüz kaydedilmedi' }}</small></div></div>
                <span class="attendance-connector"></span>
                <div class="attendance-point {{ $todayAttendance?->clock_out_at ? 'point-done' : '' }}"><span class="point-mark">{{ $todayAttendance?->clock_out_at ? '✓' : '2' }}</span><div><strong>Mesai çıkışı</strong><small>{{ $todayAttendance?->clock_out_at?->format('H:i') ?? 'Henüz kaydedilmedi' }}</small></div></div>
            </div>
            @if($openAttendance)
                <form method="POST" action="{{ route('attendance.out') }}" class="attendance-action">@csrf<button class="button button-coral button-wide" type="submit"><span class="button-stop"></span> Mesai çıkışı yap</button></form>
            @elseif(! $todayAttendance?->clock_in_at)
                <form method="POST" action="{{ route('attendance.in') }}" class="attendance-action">@csrf<button class="button button-blue button-wide" type="submit"><span class="button-play">▶</span> Mesaiye başla</button></form>
            @else
                <div class="attendance-finished"><span>✓</span> Bugünkü mesaini tamamladın.</div>
            @endif
        </section>

        <section class="panel recent-panel">
            <div class="panel-heading"><div><p class="eyebrow">SON KAYITLAR</p><h2>Rapor defterin</h2></div><a href="{{ route('reports.index') }}" class="icon-arrow" aria-label="Tüm raporları görüntüle">↗</a></div>
            <div class="recent-list">
                @forelse($recentReports as $report)
                    <a class="recent-item" href="{{ route('reports.edit', $report) }}">
                        <span class="report-type-mark type-{{ $report->type }}">{{ ['daily' => 'G', 'weekly' => 'H', 'monthly' => 'A'][$report->type] }}</span>
                        <span class="recent-copy"><strong>{{ $report->title }}</strong><small>{{ $report->type_label }} · {{ $report->period_start->format('d.m.Y') }}</small></span>
                        <span class="status-dot {{ $report->status === 'draft' ? 'dot-draft' : 'dot-done' }}"></span>
                    </a>
                @empty
                    <div class="empty-reports"><span class="empty-icon">✎</span><strong>İlk raporun seni bekliyor.</strong><p>Günün işlerini kısa notlarla kaydet; daha sonra buradan devam edebilirsin.</p><a class="text-link" href="{{ route('reports.create') }}">Rapor oluşturmaya başla ↗</a></div>
                @endforelse
            </div>
            @if($recentReports->isNotEmpty())<a class="panel-bottom-link" href="{{ route('reports.index') }}">Tüm raporları gör <span>↗</span></a>@endif
        </section>
    </div>

    <section class="period-strip">
        <div><span class="period-icon">▤</span><div><strong>Haftayı veya ayı kapat</strong><span>Öne çıkan işler, sıradaki adımlar ve takıldığın noktalar.</span></div></div>
        <div class="period-actions">
            <a href="{{ route('reports.create', ['type' => 'weekly', 'date' => $today->toDateString()]) }}">Hafta sonu raporu <span>↗</span></a>
            <a href="{{ route('reports.create', ['type' => 'monthly', 'date' => $today->toDateString()]) }}">Ay sonu raporu <span>↗</span></a>
        </div>
    </section>
@endsection
