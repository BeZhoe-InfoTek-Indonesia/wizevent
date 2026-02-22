<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_versions', function (Blueprint $table) {
            $table->id();
            $table->enum('template_type', ['email', 'whatsapp']);
            $table->unsignedBigInteger('template_id');
            $table->text('content');
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['template_type', 'template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_versions');
    }
};
