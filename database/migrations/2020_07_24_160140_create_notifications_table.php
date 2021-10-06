<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('from_user');
            $table->integer('to_user');
            $table->text('url');
            $table->text('noti_type')->nullable();
            $table->integer('action_id')->nullable();
            $table->text('read_message');
            $table->text('message')->nullable();
            $table->integer('add_type')->nullable();
            $table->integer('deal_id')->nullable();
            $table->integer('advertisement_id')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
