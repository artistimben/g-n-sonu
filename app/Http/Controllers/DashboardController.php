<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $today = today();
        $weekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $today->copy()->endOfWeek(Carbon::SUNDAY);

        $todayAttendance = $user->attendances()->whereDate('work_date', $today)->first();
        $openAttendance = $user->attendances()->whereNotNull('clock_in_at')->whereNull('clock_out_at')->latest('clock_in_at')->first();
        $todayReport = $user->reports()->where('type', 'daily')->whereDate('period_start', $today)->first();
        $weeklySubmitted = $user->reports()->where('type', 'weekly')->whereDate('period_start', $weekStart)->where('status', 'submitted')->exists();
        $recentReports = $user->reports()->latest('period_start')->latest('updated_at')->limit(5)->get();

        $weekMinutes = $user->attendances()
            ->whereBetween('work_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->whereNotNull('clock_in_at')
            ->get()
            ->sum(fn (Attendance $attendance) => $attendance->worked_minutes);

        $team = collect();
        $teamStats = null;

        if ($user->isAdmin()) {
            $team = User::with(['attendances' => fn ($query) => $query->whereDate('work_date', $today)])
                ->where('role', 'employee')
                ->orderBy('name')
                ->get();

            $teamStats = [
                'employees' => User::where('role', 'employee')->count(),
                'clocked_in' => Attendance::whereDate('work_date', $today)->whereNotNull('clock_in_at')->whereNull('clock_out_at')->count(),
                'reports_today' => Report::where('type', 'daily')->whereDate('period_start', $today)->where('status', 'submitted')->count(),
                'reports_week' => Report::where('type', 'weekly')->whereDate('period_start', $weekStart)->where('status', 'submitted')->count(),
            ];
        }

        return view('dashboard', compact(
            'user', 'today', 'todayAttendance', 'openAttendance', 'todayReport',
            'weeklySubmitted', 'recentReports', 'weekMinutes', 'team', 'teamStats', 'weekStart', 'weekEnd'
        ));
    }
}
