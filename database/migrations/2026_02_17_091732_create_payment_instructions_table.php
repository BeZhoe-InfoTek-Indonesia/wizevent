<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_instructions', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_method', ['transfer', 'bank', 'ewallet', 'qris']);
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->string('locale')->default('en');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_instructions');
    }
};
