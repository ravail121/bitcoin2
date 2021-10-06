<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdPhotoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_photo')->nullable();
            $table->string('id_photo_id')->nullable();
            $table->string('address_photo')->nullable();
            $table->integer('id_photo_status')->default('0')->nullable();
            $table->integer('id_photo_id_status')->default('0')->nullable();
            $table->integer('address_photo_status')->default('0')->nullable();
            $table->integer('verified')->default('0')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_photo');
            $table->dropColumn('id_photo_id');
            $table->dropColumn('address_photo');
            $table->dropColumn('id_photo_status');
            $table->dropColumn('id_photo_id_status');
            $table->dropColumn('address_photo_status');
            $table->dropColumn('verified');
        });
    }
}
