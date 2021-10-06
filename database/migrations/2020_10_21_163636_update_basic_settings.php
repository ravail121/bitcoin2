<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBasicSettings extends Migration
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
            // Blob Add
            $table->binary('fee_top_box');
            $table->binary('fee_bottom_box');
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
            $table->dropColumn('fee_top_box');
            $table->dropColumn('fee_bottom_box');
        });
    }
}
