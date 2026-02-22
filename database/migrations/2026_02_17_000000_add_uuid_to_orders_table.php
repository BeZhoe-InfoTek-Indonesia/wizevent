<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidToOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });

        // Backfill existing orders with uuids without firing model events
        if (class_exists(\App\Models\Order::class)) {
            \App\Models\Order::withoutEvents(function () {
                \App\Models\Order::whereNull('uuid')->get()->each(function ($o) {
                    $o->uuid = (string) Illuminate\Support\Str::uuid();
                    $o->saveQuietly();
                });
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
