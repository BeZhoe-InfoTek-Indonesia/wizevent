<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_category')->nullable();
            $table->integer('target_audience_size')->nullable();
            $table->text('target_audience_description')->nullable();
            $table->decimal('budget_target', 15, 2)->nullable();
            $table->decimal('revenue_target', 15, 2)->nullable();
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->longText('ai_concept_result')->nullable();
            $table->json('ai_budget_result')->nullable();
            $table->json('ai_pricing_result')->nullable();
            $table->json('ai_risk_result')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_plans');
    }
};
