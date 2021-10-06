<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionsToPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('question_one')->nullable();
            $table->string('question_two')->nullable();
            $table->string('answer_one')->nullable();
            $table->string('answer_two')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('question_one');
            $table->dropColumn('question_two');
            $table->dropColumn('answer_one');
            $table->dropColumn('answer_two');
        });
    }
}
