<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriasTable extends Migration
{
    public function up()
    {
        forge_schema('categorias', function($table){

            $table->id('id_categoria');
            $table->string('nombre', 45)->unique();
            // $table->timestamps();            
        });
    }

    public function down()
    {
        //
    }
}
