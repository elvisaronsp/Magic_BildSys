<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcAvulsoCarteirasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_avulso_carteiras', function (Blueprint $table) {

            $table->increments('id');
            $table->string('nome');
            $table->smallInteger('sla_start')->nullable();
            $table->smallInteger('sla_negociacao')->nullable();
            $table->smallInteger('sla_mobilizacao')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        Schema::create('qc_avulso_carteira_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('qc_avulso_carteira_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('qc_avulso_carteira_id')
                ->references('id')->on('qc_avulso_carteiras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('qc_avulso_carteira_planejamento', function (Blueprint $table){
            $table->unsignedInteger('id');
            $table->unsignedInteger('qc_avulso_carteira_id');
            $table->unsignedInteger('planejamento_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('qc_avulso_carteira_id')
                ->references('id')->on('qc_avulso_carteiras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('planejamento_datas', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('planejamento_id');
            $table->date('data');
            $table->date('data_fim');

            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qc_avulso_carteira_users');
        Schema::dropIfExists('qc_avulso_carteira_planejamento');
        Schema::dropIfExists('qc_avulso_carteiras');
        Schema::dropIfExists('planejamento_datas');
    }
}
