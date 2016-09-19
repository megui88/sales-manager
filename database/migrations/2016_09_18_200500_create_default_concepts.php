<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultConcepts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $concepts = [
            [
                'name' => 'Venta Mutual',
                'sign_operation' => '+',
            ],
            [
                'name' => 'Venta Farmacia',
                'sign_operation' => '+',
            ],
            [
                'name' => 'Nota de Credito',
                'sign_operation' => '-',
            ],
        ];
        foreach ($concepts as $concept) {
            \App\Concept::create($concept);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Concept::truncate();
    }
}
