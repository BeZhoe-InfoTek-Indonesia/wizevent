<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_plan_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_plan_id')->constrained('event_plans')->onDelete('cascade');
            $table->string('category');
            $table->text('description')->nullable();
            $table->enum('type', ['expense', 'revenue']);
            $table->decimal('planned_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_plan_line_items');
    }
};
