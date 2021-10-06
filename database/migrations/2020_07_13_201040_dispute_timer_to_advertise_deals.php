<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DisputeTimerToAdvertiseDeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertise_deals', function (Blueprint $table) {
            $table->string('dispute_timer')->nullable();	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertise_deals', function (Blueprint $table) {
            $table->dropColumn('dispute_timer');
        });
    }
}
