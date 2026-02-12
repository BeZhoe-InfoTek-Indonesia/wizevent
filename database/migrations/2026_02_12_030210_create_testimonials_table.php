<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'event_id']);
            $table->index(['user_id', 'status']);
            $table->index(['event_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
