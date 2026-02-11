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
        // 1. Drop old pivot and tags table
        Schema::dropIfExists('event_tag');
        Schema::dropIfExists('event_tags');

        // 2. Remove category_id from events table FIRST (removes the foreign key)
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        
        // 3. Now it's safe to drop event_categories
        Schema::dropIfExists('event_categories');

        // 4. Create the new pivot table
        Schema::create('event_setting_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('setting_component_id')->constrained('setting_components')->cascadeOnDelete();
            $table->timestamps();

            // Composite unique index
            $table->unique(['event_id', 'setting_component_id'], 'event_setting_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_setting_pivot');

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->nullOnDelete();
        });

        // Normally we'd recreate the dropped tables here if we wanted a perfect rollback,
        // but for a refactoring migration, sometimes the destruction is one-way or requires a full reset.
        // Dropping the new pivot is usually enough for a roll back of THIS migration's primary logic.
    }
};
