<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCryptoAddvertisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_addvertises', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('add_type');
            $table->integer('gateway_id');
            $table->integer('crypto_id');
            $table->string('amount');
            $table->string('min_amount');
            $table->string('max_amount');
            $table->longText('detail');
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
        Schema::dropIfExists('crypto_addvertises');
    }
}
