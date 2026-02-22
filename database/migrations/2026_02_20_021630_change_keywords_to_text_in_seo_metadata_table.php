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
        Schema::table('seo_metadata', function (Blueprint $table) {
            $table->text('keywords')->nullable()->change();
            $table->text('og_image')->nullable()->change();
            $table->text('canonical_url')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_metadata', function (Blueprint $table) {
            $table->string('keywords')->nullable()->change();
            $table->string('og_image')->nullable()->change();
            $table->string('canonical_url')->nullable()->change();
        });
    }
};
