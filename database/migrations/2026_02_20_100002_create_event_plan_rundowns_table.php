<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_plan_rundowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_plan_id')->constrained('event_plans')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('type')->default('other'); // ceremony, performance, break, setup, networking, registration, other
            $table->foreignId('event_plan_talent_id')->nullable()->constrained('event_plan_talents')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['event_plan_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_plan_rundowns');
    }
};
