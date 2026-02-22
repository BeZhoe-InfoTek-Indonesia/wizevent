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
        Schema::table('performers', function (Blueprint $table) {
            $table->foreignId('type_setting_component_id')->nullable()->after('photo_file_bucket_id')->constrained('setting_components')->nullOnDelete();
            $table->foreignId('profession_setting_component_id')->nullable()->after('type_setting_component_id')->constrained('setting_components')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('performers', function (Blueprint $table) {
            $table->dropForeign(['type_setting_component_id']);
            $table->dropForeign(['profession_setting_component_id']);
            $table->dropColumn(['type_setting_component_id', 'profession_setting_component_id']);
        });
    }
};
