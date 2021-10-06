<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeTimersUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->integer('dashboard_refresh_time');
            $table->integer('counter_blinking_time');
            $table->float('btc_price_factor', 3, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->dropColumn('dashboard_refresh_time');
            $table->dropColumn('counter_blinking_time');
            $table->dropColumn('btc_price_factor');
        });
    }
}
