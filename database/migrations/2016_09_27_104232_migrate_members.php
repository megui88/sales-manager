<?php

use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (($gestor = fopen("database/migrations/socios_migration.csv", "r")) !== FALSE) {
            $fila = 1;
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $numero = count($datos);
                for ($c=0; $c < $numero; $c++) {
                    try {
                        $data = explode(';', $datos[$c]);
                        $code = $data[0];
                        $name = $data[1];
                        User::createByCodeAndName($code, $name);
                    }catch (\Exception $e){
                        echo "$fila Error: " . $datos[$c] . PHP_EOL;
                        echo "Msg: " . $e->getMessage(). PHP_EOL;
                    }
                }
                ++$fila;
            }
            fclose($gestor);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      User::where('last_name', '=', ' ')->delete();
    }
}
