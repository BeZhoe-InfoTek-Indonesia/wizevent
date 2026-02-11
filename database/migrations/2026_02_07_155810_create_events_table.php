<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->dateTime('event_date');
            $table->dateTime('event_end_date')->nullable();
            $table->string('location', 500);
            $table->string('venue_name')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_place_id')->nullable();
            $table->enum('status', ['draft', 'published', 'sold_out', 'cancelled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('sales_start_at')->nullable();
            $table->timestamp('sales_end_at')->nullable();
            $table->boolean('seating_enabled')->default(false);
            $table->unsignedInteger('total_capacity')->default(0);
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('event_date');
            $table->index('published_at');
            $table->index('slug');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
