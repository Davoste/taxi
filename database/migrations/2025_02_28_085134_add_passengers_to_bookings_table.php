<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPassengersToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Skip Schema::hasColumn check
            try {
                $table->integer('passengers')->nullable();
            } catch (\Illuminate\Database\QueryException $e) {
                // Ignore if column exists (duplicate column error)
                if (strpos($e->getMessage(), 'Duplicate column name') === false) {
                    throw $e; // Re-throw if it’s a different error
                }
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('passengers');
        });
    }
}