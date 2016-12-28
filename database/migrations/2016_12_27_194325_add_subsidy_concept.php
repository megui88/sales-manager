<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubsidyConcept extends Migration
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
                'name' => 'Subsidio',
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
        \App\Concept::where('name','=','Subsidio')->first()->delete();
    }
}
