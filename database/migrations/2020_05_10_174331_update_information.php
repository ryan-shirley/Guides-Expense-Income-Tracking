<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update Payments
        Schema::table('payments', function ($table) {
            $table->string('code')->nullable($value = true);
            $table->boolean('is_cash')->nullable($value = true);
        });

        // Update Incomes
        Schema::table('incomes', function ($table) {
            $table->string('code')->nullable($value = true);
            $table->boolean('is_cash')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove payments information
        Schema::table('payments', function($table) {
            $table->dropColumn('code');
            $table->dropColumn('is_cash');
        });

        // Remove incomes information
        Schema::table('incomes', function($table) {
            $table->dropColumn('code');
            $table->dropColumn('is_cash');
        });
    }
}
