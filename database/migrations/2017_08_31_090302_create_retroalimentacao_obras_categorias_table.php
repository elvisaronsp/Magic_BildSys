<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetroalimentacaoObrasCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $seeder = new RetroalimentacaoObrasCategoriasTableSeeder();

        Schema::create('retroalimentacao_obras_categorias', function (Blueprint $table){

            $table->increments('id');

            $table->string('nome',100);

            $table->timestamps();
            $table->softDeletes();
        });

        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('retroalimentacao_obras_categorias');
        Schema::enableForeignKeyConstraints();
    }
}
