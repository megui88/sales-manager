<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('charge', 8, 2);
            $table->decimal('amount', 8, 2);
            $table->integer('installments');
            $table->integer('payer_id')->unsigned();
            $table->integer('collector_id')->unsigned();
            $table->date('first_due_date')->nullable();
            $table->string('sale_mode')->nullable();
            $table->string('period');
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
        Schema::drop('sales');
    }
}
