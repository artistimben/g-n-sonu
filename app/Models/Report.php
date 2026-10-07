<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id', 'type', 'period_start', 'period_end', 'title', 'summary',
        'completed_tasks', 'next_steps', 'blockers', 'status',
    ];

    protected function casts(): array
    {
        return ['period_start' => 'date', 'period_end' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return ['daily' => 'Gün sonu', 'weekly' => 'Hafta sonu', 'monthly' => 'Ay sonu'][$this->type] ?? $this->type;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'draft' ? 'Taslak' : 'Tamamlandı';
    }
}
