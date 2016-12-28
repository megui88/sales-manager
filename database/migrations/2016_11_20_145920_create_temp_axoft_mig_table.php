<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempAxoftMigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_axoft_mig', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('process')->default(0);
            $table->string('migrate_id');
            $table->date('fecha');
            $table->string('user_id')->nullable();
            $table->string('cod_cuenta');
            $table->string('cod_comprobante');
            $table->string('comprobante');
            $table->text('leyenda');
            $table->decimal('debe');
            $table->decimal('haber');
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
        Schema::drop('temp_axoft_mig');
    }
}
