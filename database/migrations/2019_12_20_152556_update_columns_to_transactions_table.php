<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsToTransactionsTable extends Migration
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
                'amount',
                'fee',
            ]);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 16, 8)
                  ->nullable()
                  ->after('type');
            $table->decimal('fee', 16, 8)
                  ->nullable()
                  ->after('type');
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
            $table->decimal('amount', 8, 2)
                  ->nullable()
                  ->after('type');
            $table->decimal('fee', 8, 2)
                  ->nullable()
                  ->after('type');
        });
    }
}
