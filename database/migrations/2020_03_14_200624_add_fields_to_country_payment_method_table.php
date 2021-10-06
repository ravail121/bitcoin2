<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCountryPaymentMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country_payment_method', function (Blueprint $table) {
            $table->unsignedInteger('country_id');
            $table->unsignedInteger('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country_payment_method', function (Blueprint $table) {
            $table->dropColumn('country_id');
            $table->dropColumn('payment_method_id');
        });
    }
}
