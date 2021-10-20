<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserLogin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login', function (Blueprint $table) {
$table->unsignedInteger('user_id')->index();
            $table->bigIncrements('id');
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->string('location')->nullable();
            $table->string('user_ip')->nullable();
            $table->string('country_name')->nullable();
            $table->string('details')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('action')->nullable();
            $table->integer('is_country_changed')->nullable();
            
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
        //
    }
}
