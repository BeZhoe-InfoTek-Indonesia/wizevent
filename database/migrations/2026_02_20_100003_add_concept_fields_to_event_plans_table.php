<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            $table->string('concept_status')->default('brainstorm')->after('ai_concept_result'); // brainstorm, drafted, finalized, synced
            $table->string('theme')->nullable()->after('concept_status');
            $table->string('tagline')->nullable()->after('theme');
            $table->text('narrative_summary')->nullable()->after('tagline');
            $table->timestamp('concept_synced_at')->nullable()->after('narrative_summary');
        });
    }

    public function down(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            $table->dropColumn(['concept_status', 'theme', 'tagline', 'narrative_summary', 'concept_synced_at']);
        });
    }
};
