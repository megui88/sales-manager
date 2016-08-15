<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dues', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('sale_id');
            $table->decimal('amount_of_quota', 8, 2);
            $table->integer('number_of_quota');
            $table->uuid('payer_id');
            $table->string('period');
            $table->date('due_date')->nullable();
            $table->string('state');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dues');
    }
}
