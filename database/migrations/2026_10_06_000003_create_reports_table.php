<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 16);
            $table->date('period_start');
            $table->date('period_end');
            $table->string('title', 140);
            $table->text('summary')->nullable();
            $table->longText('completed_tasks');
            $table->longText('next_steps')->nullable();
            $table->longText('blockers')->nullable();
            $table->string('status', 16)->default('submitted');
            $table->timestamps();
            $table->unique(['user_id', 'type', 'period_start']);
            $table->index(['type', 'period_start', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
