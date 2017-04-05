<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanilhasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planilhas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('tipo_orcamento_id');
            $table->foreign('tipo_orcamento_id')->references('id')->on('orcamento_tipos')->onDelete('cascade')->onUpdate('cascade');
            $table->string('arquivo')->nullable();
            $table->longText('json')->nullable();
            $table->string('status')->nullable();
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
        Schema::table('planilhas', function (Blueprint $table) {
            $table->dropForeign(['planilhas_user_id_foreign']);
        });

        Schema::drop('planilhas');
    }
}
