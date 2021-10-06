<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdvertisements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->boolean('auto_max')->after('max_amount')->default(false);
            $table->boolean('allow_email')->after('auto_max')->default(false);
            $table->boolean('allow_phone')->after('allow_email')->default(false);
            $table->boolean('allow_id')->after('allow_phone')->default(false);
            $table->string('init_message')->after('allow_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('auto_max');
            $table->dropColumn('allow_email');     
            $table->dropColumn('allow_phone'); 
            $table->dropColumn('allow_id');    
            $table->dropColumn('init_message');          
        });
    }
}
