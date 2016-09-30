<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMigrateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('migrates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('checksum');
            $table->string('description');
            $table->string('status');
            $table->text('errors');
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
        Schema::drop('migrates');
    }
}
