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
        Schema::table('events', function (Blueprint $table) {
            $table->string('target_audience')->nullable()->after('description');
            $table->text('key_activities')->nullable()->after('target_audience');
            $table->string('ai_tone_style')->nullable()->after('key_activities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['target_audience', 'key_activities', 'ai_tone_style']);
        });
    }
};
