<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user')->latest('period_start')->latest('updated_at');
        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        } elseif ($request->filled('employee')) {
            $query->where('user_id', $request->integer('employee'));
        }

        if (in_array($request->query('type'), ['daily', 'weekly', 'monthly'], true)) {
            $query->where('type', $request->query('type'));
        }

        $reports = $query->paginate(12)->withQueryString();
        $employees = $request->user()->isAdmin()
            ? User::where('role', 'employee')->orderBy('name')->get()
            : collect();

        return view('reports.index', compact('reports', 'employees'));
    }

    public function create(Request $request)
    {
        $type = in_array($request->query('type'), ['daily', 'weekly', 'monthly'], true)
            ? $request->query('type')
            : 'daily';
        $date = $request->query('date', today()->toDateString());
        abort_unless(is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date), 404);
        try {
            $date = Carbon::createFromFormat('!Y-m-d', $date)->toDateString();
        } catch (\Throwable) {
            abort(404);
        }

        [$start, $end] = $this->period($type, $date);
        $report = $request->user()->reports()
            ->where('type', $type)
            ->whereDate('period_start', $start)
            ->first();

        return view('reports.form', compact('type', 'date', 'start', 'end', 'report'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateContent($request);
        [$start, $end] = $this->period($validated['type'], $validated['date']);
        $title = trim($validated['title'] ?? '') ?: $this->defaultTitle($validated['type'], $start, $end);

        $request->user()->reports()->updateOrCreate(
            ['type' => $validated['type'], 'period_start' => $start->toDateString()],
            [
                'period_end' => $end->toDateString(),
                'title' => $title,
                'summary' => $validated['summary'] ?? null,
                'completed_tasks' => $validated['completed_tasks'] ?? '',
                'next_steps' => $validated['next_steps'] ?? null,
                'blockers' => $validated['blockers'] ?? null,
                'status' => $validated['action'] === 'draft' ? 'draft' : 'submitted',
            ],
        );

        $message = $validated['action'] === 'draft' ? 'Taslağınız kaydedildi.' : 'Raporunuz tamamlandı ve kaydedildi.';

        return redirect()->route('reports.index')->with('success', $message);
    }

    public function edit(Request $request, Report $report)
    {
        abort_unless($request->user()->id === $report->user_id, 403);

        $type = $report->type;
        $date = $report->period_start->toDateString();
        $start = $report->period_start;
        $end = $report->period_end;

        return view('reports.form', compact('type', 'date', 'start', 'end', 'report'));
    }

    public function update(Request $request, Report $report)
    {
        abort_unless($request->user()->id === $report->user_id, 403);
        $validated = $this->validateContent($request, editing: true);
        $start = $report->period_start;
        $end = $report->period_end;

        $report->update([
            'title' => trim($validated['title'] ?? '') ?: $this->defaultTitle($report->type, $start, $end),
            'summary' => $validated['summary'] ?? null,
            'completed_tasks' => $validated['completed_tasks'] ?? '',
            'next_steps' => $validated['next_steps'] ?? null,
            'blockers' => $validated['blockers'] ?? null,
            'status' => $validated['action'] === 'draft' ? 'draft' : 'submitted',
        ]);

        return redirect()->route('reports.index')->with('success', $validated['action'] === 'draft' ? 'Taslağınız güncellendi.' : 'Raporunuz güncellendi.');
    }

    private function validateContent(Request $request, bool $editing = false): array
    {
        $action = $request->input('action', 'submit');
        abort_unless(in_array($action, ['draft', 'submit'], true), 422);

        $rules = [
            'title' => [$action === 'submit' ? 'required' : 'nullable', 'string', 'max:140'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'completed_tasks' => [$action === 'submit' ? 'required' : 'nullable', 'string'],
            'next_steps' => ['nullable', 'string'],
            'blockers' => ['nullable', 'string'],
            'action' => ['required', 'in:draft,submit'],
        ];

        if (! $editing) {
            $rules['type'] = ['required', 'in:daily,weekly,monthly'];
            $rules['date'] = ['required', 'date_format:Y-m-d'];
        }

        return $request->validate($rules, [
            'title.required' => 'Rapor başlığını girin.',
            'completed_tasks.required' => 'Tamamlanan işleri yazın.',
            'date.date_format' => 'Geçerli bir tarih seçin.',
        ]);
    }

    private function period(string $type, string $date): array
    {
        $day = Carbon::parse($date)->startOfDay();

        return match ($type) {
            'weekly' => [$day->copy()->startOfWeek(Carbon::MONDAY), $day->copy()->endOfWeek(Carbon::SUNDAY)],
            'monthly' => [$day->copy()->startOfMonth(), $day->copy()->endOfMonth()],
            default => [$day, $day->copy()],
        };
    }

    private function defaultTitle(string $type, Carbon $start, Carbon $end): string
    {
        return match ($type) {
            'weekly' => 'Hafta sonu raporu · ' . $start->format('d.m') . ' – ' . $end->format('d.m.Y'),
            'monthly' => 'Ay sonu raporu · ' . $start->translatedFormat('F Y'),
            default => 'Gün sonu raporu · ' . $start->format('d.m.Y'),
        };
    }
}
