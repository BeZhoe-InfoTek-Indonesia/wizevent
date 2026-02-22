<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['hero', 'section', 'mobile']);
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->enum('link_target', ['_self', '_blank'])->default('_self');
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->unsignedInteger('click_count')->default(0);
            $table->unsignedInteger('impression_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('is_active');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
