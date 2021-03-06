<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMascaraPadraoObraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
		Schema::table('mascara_padrao', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('mascara_padrao')->delete();			
            
			if (Schema::hasColumn('mascara_padrao', 'obra_id'))
			{
				Schema::table('mascara_padrao', function (Blueprint $table)
				{
					$table->dropForeign(['obra_id']);  
					$table->dropColumn(['obra_id']);
				});
			}
			
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	   Schema::dropIfExists('mascara_padrao');
    }
}
