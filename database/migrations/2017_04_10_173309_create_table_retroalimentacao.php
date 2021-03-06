<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRetroalimentacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retroalimentacao_obras', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('user_id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('obra_id')->references('id')->on('obras')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('retroalimentacao_obras');
    }
}
