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
        Schema::create('file_buckets', function (Blueprint $table) {
            $table->id();
            $table->string('fileable_type');
            $table->unsignedBigInteger('fileable_id');
            $table->string('bucket_type', 100);
            $table->json('collection')->nullable();
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path', 500);
            $table->string('url', 500)->nullable();
            $table->string('mime_type', 100);
            $table->unsignedInteger('file_size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->json('metadata')->nullable();
            $table->json('sizes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['fileable_type', 'fileable_id']);
            $table->index('bucket_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_buckets');
    }
};
