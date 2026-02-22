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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('status');
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->string('image_path')->nullable()->after('is_featured');
            $table->string('image_original_name')->nullable()->after('image_path');
            $table->string('image_mime_type')->nullable()->after('image_original_name');
            $table->unsignedInteger('image_width')->nullable()->after('image_mime_type');
            $table->unsignedInteger('image_height')->nullable()->after('image_width');
            $table->unsignedBigInteger('image_file_size')->nullable()->after('image_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn([
                'is_published',
                'is_featured',
                'image_path',
                'image_original_name',
                'image_mime_type',
                'image_width',
                'image_height',
                'image_file_size',
            ]);
        });
    }
};
