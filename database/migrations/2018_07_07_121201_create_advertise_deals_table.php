<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertiseDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertise_deals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('add_id');
            $table->integer('to_user_id');
            $table->integer('from_user_id');
            $table->string('trans_id');
            $table->string('amount_to');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertise_deals');
    }
}
