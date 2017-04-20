<?php

namespace App\Http\Controllers;


use App\Models\Lembrete;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanejamentoController extends AppBaseController
{
    public function lembretes(Request $request){
        $lembretes = Lembrete::join('insumo_grupos','insumo_grupos.id','=','lembretes.insumo_grupo_id')
            ->join('insumos','insumos.insumo_grupo_id','=','insumo_grupos.id')
            ->join('planejamento_compras','planejamento_compras.insumo_id','=','insumos.id')
            ->join('planejamentos', 'planejamentos.id','=','planejamento_compras.planejamento_id')
            ->join('obras', 'obras.id','=','planejamentos.obra_id')
            ->whereNull('planejamentos.deleted_at')
            ->select([
                'lembretes.id',
                DB::raw("CONCAT(obras.nome,' - ',planejamentos.tarefa,' - ', lembretes.nome) title"),
                DB::raw("'event-info' as class"),
                DB::raw("CONCAT('/compras/',planejamentos.id,'/obrasInsumos/',insumo_grupos.id) as url"),
                DB::raw("DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY),'%d/%m/%Y') as inicio"),
                DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY))*1000 as start"),
                DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY))*1000 as end"),
            ]);

            if($request->from || $request->to){
                if($request->from){
                    $from = date('Y-m-d',$request->from/1000);
                    $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY)'), '>=', $from);
                }
                if($request->to){
                    $to = date('Y-m-d',$request->to/1000);
                    $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY)'), '<=', $to);
                }
            }else{
                $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL lembretes.`dias_prazo_minimo` DAY)'), '<=',DB::raw('CURRENT_DATE'));
            }

            if($request->obra_id){
                $lembretes->where('planejamentos.obra_id', $request->obra_id);
            }
            $lembretes = $lembretes->distinct()->get();

        return response()->json([
            'success'=>true,
            'result'=>$lembretes
        ]);
    }
}