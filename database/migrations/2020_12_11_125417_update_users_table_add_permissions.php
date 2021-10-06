<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAddPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('permission_withdraw')->default(true);
            $table->boolean('permission_send')->default(true);
            $table->boolean('permission_buy')->default(true);
            $table->boolean('permission_sell')->default(true);
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
            $table->dropColumn('permission_withdraw');
            $table->dropColumn('permission_send');     
            $table->dropColumn('permission_buy'); 
            $table->dropColumn('permission_sell');            
        });
    }
}
