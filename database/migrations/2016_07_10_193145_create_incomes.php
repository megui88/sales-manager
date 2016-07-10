<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id')->unsigned();
            $table->decimal('charge', 8, 2);
            $table->decimal('amount_of_quota', 8, 2);
            $table->integer('number_of_quota');
            $table->integer('payer_id')->unsigned();
            $table->integer('collector_id')->unsigned();
            $table->string('period');
            $table->date('due_date')->nullable();
            $table->string('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('incomes');
    }
}
