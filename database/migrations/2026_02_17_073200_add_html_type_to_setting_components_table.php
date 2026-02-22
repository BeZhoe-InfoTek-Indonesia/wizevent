<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE setting_components MODIFY COLUMN type ENUM('string', 'integer', 'boolean', 'html') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE setting_components MODIFY COLUMN type ENUM('string', 'integer', 'boolean') NOT NULL");
    }
};
