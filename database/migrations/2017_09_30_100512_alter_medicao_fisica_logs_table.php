<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMedicaoFisicaLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
		Schema::table('medicao_fisica_logs', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('medicao_fisica_logs')->delete();			

			$table->text('observacao')->nullable();
			
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('observacao');
    }
}
