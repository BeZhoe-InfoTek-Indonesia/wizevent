<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_plan_talents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_plan_id')->constrained('event_plans')->cascadeOnDelete();
            $table->foreignId('performer_id')->constrained('performers')->cascadeOnDelete();
            $table->decimal('planned_fee', 15, 2)->nullable();
            $table->decimal('actual_fee', 15, 2)->nullable();
            $table->time('slot_time')->nullable();
            $table->integer('slot_duration_minutes')->nullable();
            $table->integer('performance_order')->default(0);
            $table->string('contract_status')->default('draft'); // draft, negotiating, confirmed, cancelled
            $table->text('rider_notes')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('budget_line_item_id')->nullable()->constrained('event_plan_line_items')->nullOnDelete();
            $table->timestamps();

            $table->unique(['event_plan_id', 'performer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_plan_talents');
    }
};
