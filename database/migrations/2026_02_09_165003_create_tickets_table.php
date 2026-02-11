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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->string('holder_name')->nullable();
            $table->enum('status', ['active', 'used', 'cancelled'])->default('active');
            $table->text('qr_code_content')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->index(['order_item_id', 'status']);
            $table->index(['ticket_type_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
