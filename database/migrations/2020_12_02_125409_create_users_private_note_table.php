<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPrivateNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_private_note', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('from_user_id')->index();
            $table->unsignedInteger('to_user_id')->index();
            $table->binary('note');
            $table->timestamps();

            $table->foreign('from_user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('to_user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_private_note');
    }
}
