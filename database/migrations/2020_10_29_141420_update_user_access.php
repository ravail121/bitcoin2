<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_logins', function (Blueprint $table) {
            $table->string('browser')->after('details')->nullable();
            $table->string('platform')->after('browser')->nullable();
            $table->string('action')->after('platform')->nullable();
            $table->string('country_name')->after('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_logins', function (Blueprint $table) {
            $table->dropColumn('browser');
            $table->dropColumn('action');     
            $table->dropColumn('country_name'); 
            $table->dropColumn('platform');            
        });
    }
}
