<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'main_amo',
            ]);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('main_amo', 16, 8)
                  ->default(0)
                  ->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('main_amo', 8, 2)
                  ->default(0)
                  ->after('amount');
        });
    }
}
