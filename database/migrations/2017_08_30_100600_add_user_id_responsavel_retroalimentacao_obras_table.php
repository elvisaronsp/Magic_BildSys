<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdResponsavelRetroalimentacaoObrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retroalimentacao_obras', function(Blueprint $table){

            $table->unsignedInteger('user_id_responsavel')->after('user_id');
            $table->foreign('user_id_responsavel')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retroalimentacao_obras', function(Blueprint $table){
            $table->dropColumn('user_id_responsavel');
        });
    }
}
