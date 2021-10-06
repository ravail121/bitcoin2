<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreMainAmoInTrxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trxes', function (Blueprint $table) {
            $table->string('pre_main_amo')->after('user_id')->default('0');
        });
        // Schema::table('transactions', function (Blueprint $table) {
        //     $table->float('pre_main_amo',16,8)->after('fee');
        // });
        Schema::table('withdraw_requests', function (Blueprint $table) {
            // $table->float('pre_main_amo',16,8)->after('address');
            $table->float('fee',16,8)->after('main_amo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trxes', function (Blueprint $table) {
            $table->dropColumn('pre_main_amo');
        });
        // Schema::table('transactions', function (Blueprint $table) {
        //     $table->dropColumn('pre_main_amo');
        // });
        Schema::table('withdraw_requests', function (Blueprint $table) {
            // $table->dropColumn('pre_main_amo');
            $table->dropColumn('fee');
        });
    }
}
