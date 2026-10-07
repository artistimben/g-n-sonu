<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = $request->user();
        $now = now();

        if ($user->attendances()->whereNotNull('clock_in_at')->whereNull('clock_out_at')->exists()) {
            return back()->withErrors(['attendance' => 'Açık bir mesainiz var. Önce mesai çıkışı yapın.']);
        }

        $record = $user->attendances()->whereDate('work_date', $now->toDateString())->first();
        if ($record?->clock_in_at) {
            return back()->withErrors(['attendance' => 'Bugünün mesai girişi zaten kaydedilmiş.']);
        }

        if (! $record) {
            $record = new Attendance(['work_date' => $now->toDateString()]);
            $user->attendances()->save($record);
        }

        $record->update(['clock_in_at' => $now]);

        return back()->with('success', 'Mesai girişiniz ' . $now->format('H:i') . ' olarak kaydedildi.');
    }

    public function clockOut(Request $request)
    {
        $attendance = $request->user()->attendances()
            ->whereNotNull('clock_in_at')
            ->whereNull('clock_out_at')
            ->latest('clock_in_at')
            ->first();

        if (! $attendance) {
            return back()->withErrors(['attendance' => 'Açık mesainiz bulunmuyor.']);
        }

        $now = now();
        if ($now->lessThan($attendance->clock_in_at)) {
            return back()->withErrors(['attendance' => 'Çıkış saati giriş saatinden önce olamaz.']);
        }

        $attendance->update(['clock_out_at' => $now]);

        return back()->with('success', 'Mesai çıkışınız ' . $now->format('H:i') . ' olarak kaydedildi.');
    }
}
