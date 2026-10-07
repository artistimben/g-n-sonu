@extends('layouts.app')

@section('title', 'Raporlar')

@section('content')
    <section class="page-heading">
        <div><p class="eyebrow">ÜRETİM KAYITLARI</p><h1>{{ auth()->user()->isAdmin() ? 'Ekip raporları' : 'Rapor defterin' }}</h1><p class="heading-subtitle">Günlük, haftalık ve aylık üretim notlarını bir arada gör.</p></div>
        <a class="button button-dark" href="{{ route('reports.create') }}"><span class="button-plus">+</span> Yeni rapor yaz</a>
    </section>

    <section class="report-toolbar">
        <div class="filter-tabs" aria-label="Rapor türü filtresi">
            <a class="filter-tab {{ !request('type') ? 'selected' : '' }}" href="{{ route('reports.index', request()->except('type', 'page')) }}">Tümü</a>
            <a class="filter-tab {{ request('type') === 'daily' ? 'selected' : '' }}" href="{{ route('reports.index', array_merge(request()->except('page'), ['type' => 'daily'])) }}">Gün sonu</a>
            <a class="filter-tab {{ request('type') === 'weekly' ? 'selected' : '' }}" href="{{ route('reports.index', array_merge(request()->except('page'), ['type' => 'weekly'])) }}">Hafta sonu</a>
            <a class="filter-tab {{ request('type') === 'monthly' ? 'selected' : '' }}" href="{{ route('reports.index', array_merge(request()->except('page'), ['type' => 'monthly'])) }}">Ay sonu</a>
        </div>
        @if(auth()->user()->isAdmin())
            <form method="GET" class="employee-filter">
                @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
                <label for="employee-filter">Ekip üyesi</label>
                <select name="employee" id="employee-filter" onchange="this.form.submit()"><option value="">Tüm ekip</option>@foreach($employees as $employee)<option value="{{ $employee->id }}" @selected((string) request('employee') === (string) $employee->id)>{{ $employee->name }}</option>@endforeach</select>
            </form>
        @endif
    </section>

    <div class="reports-list">
        @forelse($reports as $report)
            <article class="report-card">
                <div class="report-card-top">
                    <div class="report-ident"><span class="report-type-mark type-{{ $report->type }}">{{ ['daily' => 'G', 'weekly' => 'H', 'monthly' => 'A'][$report->type] }}</span><div><span class="report-kind">{{ $report->type_label }} <i>·</i> {{ $report->period_start->format('d.m.Y') }}@if($report->period_start->ne($report->period_end)<span> – {{ $report->period_end->format('d.m.Y') }}</span>@endif</span><h2>{{ $report->title }}</h2></div></div>
                    <div class="report-card-meta">
                        <span class="report-status {{ $report->status === 'draft' ? 'report-status-draft' : 'report-status-done' }}"><i></i>{{ $report->status_label }}</span>
                        @if(auth()->user()->isAdmin())<span class="report-author">{{ $report->user->name }}</span>@else<a class="icon-arrow report-edit" href="{{ route('reports.edit', $report) }}" aria-label="Raporu düzenle">↗</a>@endif
                    </div>
                </div>
                @if($report->summary)<p class="report-summary">{{ $report->summary }}</p>@endif
                <div class="report-content-grid">
                    <div><h3>Tamamlanan işler</h3><p>{{ $report->completed_tasks ?: 'Henüz eklenmedi.' }}</p></div>
                    @if($report->next_steps)<div><h3>Sıradaki adımlar</h3><p>{{ $report->next_steps }}</p></div>@endif
                    @if($report->blockers)<div><h3>Takılan noktalar</h3><p>{{ $report->blockers }}</p></div>@endif
                </div>
                <div class="report-updated">Son güncelleme {{ $report->updated_at->format('d.m.Y · H:i') }}</div>
            </article>
        @empty
            <div class="empty-state"><span class="empty-icon">▤</span><h2>Henüz rapor yok.</h2><p>İlk notunu oluşturduğunda burada görünecek.</p><a class="button button-blue" href="{{ route('reports.create') }}">İlk raporu yaz <span>↗</span></a></div>
        @endforelse
    </div>
    @if($reports->hasPages())<div class="pagination-wrap">{{ $reports->links() }}</div>@endif
@endsection
