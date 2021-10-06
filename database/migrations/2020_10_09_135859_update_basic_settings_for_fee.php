<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBasicSettingsForFee extends Migration
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
            // withdraw
            $table->float('withdraw_external_fixed_fee', 16, 8);
            $table->float('withdraw_external_percentage_fee', 16, 2);
            // deposit
            $table->float('deposit_external_fixed_fee', 16, 8);
            $table->float('deposit_external_percentage_fee', 16, 2);
            // buy
            $table->float('buy_advertiser_fixed_fee', 16, 8);
            $table->float('buy_advertiser_percentage_fee', 16, 2);

            $table->float('buy_user_fixed_fee', 16, 8);
            $table->float('buy_user_percentage_fee', 16, 2);
            // sell
            $table->float('sell_advertiser_fixed_fee', 16, 8);
            $table->float('sell_advertiser_percentage_fee', 16, 2);
            
            $table->float('sell_user_fixed_fee', 16, 8);
            $table->float('sell_user_percentage_fee', 16, 2);
            // send
            $table->float('send_internal_fixed_fee', 16, 8);
            $table->float('send_internal_percentage_fee', 16, 2);
            // receive
            $table->float('receive_internal_fixed_fee', 16, 8);
            $table->float('receive_internal_percentage_fee', 16, 2);
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
            $table->dropColumn('withdraw_external_fixed_fee');
            $table->dropColumn('withdraw_external_percentage_fee');
            $table->dropColumn('deposit_external_fixed_fee');
            $table->dropColumn('deposit_external_percentage_fee');
            $table->dropColumn('buy_advertiser_fixed_fee');
            $table->dropColumn('buy_advertiser_percentage_fee');
            $table->dropColumn('buy_user_fixed_fee');
            $table->dropColumn('buy_user_percentage_fee');
            $table->dropColumn('sell_advertiser_fixed_fee');
            $table->dropColumn('sell_advertiser_percentage_fee');
            $table->dropColumn('sell_user_fixed_fee');
            $table->dropColumn('sell_user_percentage_fee');
            $table->dropColumn('send_internal_fixed_fee');
            $table->dropColumn('send_internal_percentage_fee');
            $table->dropColumn('receive_internal_fixed_fee');
            $table->dropColumn('receive_internal_percentage_fee');
        });
    }
}
