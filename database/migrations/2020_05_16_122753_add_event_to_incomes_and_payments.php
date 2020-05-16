<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventToIncomesAndPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add event reference for payments
        Schema::table('payments', function ($table) {
            $table->unsignedBigInteger('event_id')->nullable($value = true);
            $table->foreign('event_id')->references('id')->on('events');
        });

        // Add event reference for incomes
        Schema::table('incomes', function ($table) {
            $table->unsignedBigInteger('event_id')->nullable($value = true);
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove event reference
        Schema::table('payments', function($table) {
            $table->dropColumn('event_id');
        });

        // Remove event reference
        Schema::table('incomes', function($table) {
            $table->dropColumn('event_id');
        });
    }
}
