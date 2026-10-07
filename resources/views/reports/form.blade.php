@extends('layouts.app')

@section('title', $report ? 'Raporu düzenle' : 'Yeni rapor')

@section('content')
    @php($typeLabels = ['daily' => 'Gün sonu', 'weekly' => 'Hafta sonu', 'monthly' => 'Ay sonu'])
    <section class="page-heading form-heading">
        <div><p class="eyebrow">{{ $report ? 'RAPORU DÜZENLE' : 'YENİ KAYIT' }}</p><h1>{{ $report ? 'Notlarına devam et.' : 'Üretimini kayda al.' }}</h1><p class="heading-subtitle">Tamamlananları, sıradaki işleri ve desteğe ihtiyaç duyduğun noktaları yaz.</p></div>
        <a class="button button-quiet" href="{{ route('reports.index') }}">← Raporlara dön</a>
    </section>

    <section class="report-form-shell">
        @if($report)
            <form method="POST" action="{{ route('reports.update', $report) }}" class="report-form" data-period-form>
                @method('PUT')
        @else
            <form method="POST" action="{{ route('reports.store') }}" class="report-form" data-period-form>
        @endif
            @csrf
            @if(! $report)
                <fieldset class="type-choice-field">
                    <legend>Rapor dönemi</legend>
                    <div class="type-choice-row">
                        @foreach($typeLabels as $value => $label)
                            <label class="type-choice {{ old('type', $type) === $value ? 'is-selected' : '' }}"><input type="radio" name="type" value="{{ $value }}" @checked(old('type', $type) === $value)><span class="type-choice-icon">{{ ['daily' => 'G', 'weekly' => 'H', 'monthly' => 'A'][$value] }}</span><span><strong>{{ $label }}</strong><small>{{ ['daily' => 'Tek iş günü', 'weekly' => 'Pazartesi – pazar', 'monthly' => 'Takvim ayı'][$value] }}</small></span></label>
                        @endforeach
                    </div>
                </fieldset>
                <div class="form-period-row">
                    <label for="report-date">{{ $type === 'daily' ? 'Rapor tarihi' : 'Dönem içinden bir tarih seç' }}</label>
                    <input id="report-date" type="date" name="date" value="{{ old('date', $date) }}" required>
                    <span class="period-preview" data-period-preview>{{ $start->format('d.m.Y') }}@if($start->ne($end)) – {{ $end->format('d.m.Y') }}@endif</span>
                </div>
            @else
                <div class="locked-period"><span class="report-type-mark type-{{ $report->type }}">{{ ['daily' => 'G', 'weekly' => 'H', 'monthly' => 'A'][$report->type] }}</span><div><strong>{{ $typeLabels[$report->type] }} raporu</strong><span>{{ $start->format('d.m.Y') }}@if($start->ne($end)) – {{ $end->format('d.m.Y') }}@endif</span></div><span class="locked-badge">DÖNEM SABİT</span></div>
            @endif

            <div class="form-divider"></div>
            <div class="form-field"><label for="title">Rapor başlığı</label><input id="title" type="text" name="title" maxlength="140" value="{{ old('title', $report?->title ?? '') }}" placeholder="Örn. Marka lansmanı ve sosyal medya içerikleri"><small>Raporunu daha sonra kolayca bulabilmek için kısa bir başlık ekle.</small></div>
            <div class="form-field"><label for="summary">Kısa özet <span>İsteğe bağlı</span></label><textarea id="summary" name="summary" rows="2" maxlength="2000" placeholder="Bu dönemin en önemli çıktısı neydi?">{{ old('summary', $report?->summary ?? '') }}</textarea></div>
            <div class="form-field"><label for="completed_tasks">Tamamlanan işler <span class="required-mark">*</span></label><textarea id="completed_tasks" name="completed_tasks" rows="6" placeholder="Her işi ayrı satırda yazabilirsin.&#10;Örn. Yeni kampanya için 3 görsel taslağı hazırlandı.">{{ old('completed_tasks', $report?->completed_tasks ?? '') }}</textarea><small>Taslak kaydetmek için boş bırakabilirsin; tamamlamak için en az bir çıktı ekle.</small></div>
            <div class="form-field"><label for="next_steps">Sıradaki adımlar <span>İsteğe bağlı</span></label><textarea id="next_steps" name="next_steps" rows="3" placeholder="Bir sonraki çalışma döneminde neler yapılacak?">{{ old('next_steps', $report?->next_steps ?? '') }}</textarea></div>
            <div class="form-field"><label for="blockers">Takılan noktalar <span>İsteğe bağlı</span></label><textarea id="blockers" name="blockers" rows="3" placeholder="Bekleyen onaylar, ihtiyaç duyduğun destek veya riskler…">{{ old('blockers', $report?->blockers ?? '') }}</textarea></div>
            <div class="form-actions"><span class="form-save-note">Rapor yalnızca senin hesabına kaydedilir.</span><div><button class="button button-quiet" type="submit" name="action" value="draft">Taslak kaydet</button><button class="button button-blue" type="submit" name="action" value="submit">Raporu tamamla <span>↗</span></button></div></div>
        </form>
    </section>
@endsection
