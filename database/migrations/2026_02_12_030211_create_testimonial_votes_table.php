<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonial_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_helpful')->default(true);
            $table->timestamps();

            $table->unique(['testimonial_id', 'user_id']);
            $table->index(['testimonial_id', 'is_helpful']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonial_votes');
    }
};
