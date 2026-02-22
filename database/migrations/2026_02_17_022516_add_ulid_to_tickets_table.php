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
        Schema::table('tickets', function (Blueprint $table) {
            $table->ulid('ulid')->after('id')->nullable();
        });

        // Generate ULIDs for existing tickets
        \Illuminate\Support\Facades\DB::table('tickets')->get()->each(function ($ticket) {
            \Illuminate\Support\Facades\DB::table('tickets')
                ->where('id', $ticket->id)
                ->update(['ulid' => (string) \Illuminate\Support\Str::ulid()]);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->ulid('ulid')->unique()->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });
    }
};
