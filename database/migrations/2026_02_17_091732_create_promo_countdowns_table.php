<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_countdowns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->timestamp('target_date');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('display_location', ['home', 'events', 'checkout', 'all'])->default('all');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_countdowns');
    }
};
