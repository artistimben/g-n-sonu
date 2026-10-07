<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'work_date', 'clock_in_at', 'clock_out_at'];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'clock_in_at' => 'datetime',
            'clock_out_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getWorkedMinutesAttribute(): int
    {
        if (! $this->clock_in_at) {
            return 0;
        }

        return (int) $this->clock_in_at->diffInMinutes($this->clock_out_at ?? now());
    }
}
