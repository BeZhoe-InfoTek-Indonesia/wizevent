<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ticket_types');

        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->unsigned();
            $table->integer('sold_count')->unsigned()->default(0);
            $table->integer('min_purchase')->unsigned()->default(1);
            $table->integer('max_purchase')->unsigned()->default(10);
            $table->timestamp('sales_start_at')->nullable();
            $table->timestamp('sales_end_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamps();

            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
