<?php

namespace App\Http\Controllers;


use App\Models\Lembrete;
use App\Models\Planejamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanejamentoController extends AppBaseController
{
    public function lembretes(Request $request)
    {
        $lembretes = Lembrete::join('insumo_grupos', 'insumo_grupos.id', '=', 'lembretes.insumo_grupo_id')
            ->join('insumos', 'insumos.insumo_grupo_id', '=', 'insumo_grupos.id')
            ->join('planejamento_compras', 'planejamento_compras.insumo_id', '=', 'insumos.id')
            ->join('planejamentos', 'planejamentos.id', '=', 'planejamento_compras.planejamento_id')
            ->join('obras', 'obras.id', '=', 'planejamentos.obra_id')
            ->join('obra_users', 'obra_users.obra_id', '=', 'obras.id')
            ->whereNull('planejamentos.deleted_at')
            ->where('lembretes.lembrete_tipo_id', 1)
            ->where('obra_users.user_id', Auth::user()->id)
            ->select([
                'planejamentos.id',
                'obras.nome as obra',
                'planejamentos.tarefa',
                DB::raw("CONCAT(obras.nome,' - ',planejamentos.tarefa,' - ', lembretes.nome) title"),
                DB::raw("'event-info' as class"),
                DB::raw("CONCAT('/compras/obrasInsumos?planejamento_id=',planejamentos.id,'&insumo_grupos_id=',insumo_grupos.id) as url"),
                DB::raw("DATE_FORMAT(DATE_SUB(planejamentos.data, INTERVAL (
                        IFNULL(
                            (
                                SELECT
                                    SUM(L.dias_prazo_minimo) prazo
                                FROM
                                    lembretes L
                                JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                WHERE
                                    EXISTS(
                                        SELECT
                                            1
                                        FROM
                                            insumos I
                                        WHERE
                                            I.id = insumos.id
                                        AND I.insumo_grupo_id = IG.id
                                    )
                                AND L.deleted_at IS NULL
                            ) ,
                            0
                        ) + IFNULL(
                            (
                                SELECT
                                    SUM(dias_prazo) prazo
                                FROM
                                    workflow_alcadas
                                WHERE
                                    EXISTS(
                                        SELECT
                                            1
                                        FROM
                                            workflow_usuarios
                                        WHERE
                                            workflow_alcada_id = workflow_alcadas.id
                                    )
                            ) ,
                            0
                        )
                    ) DAY),'%d/%m/%Y') as inicio"),
                DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL (
                            IFNULL(
                                (
                                    SELECT
                                        SUM(L.dias_prazo_minimo) prazo
                                    FROM
                                        lembretes L
                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                    WHERE
                                        EXISTS(
                                            SELECT
                                                1
                                            FROM
                                                insumos I
                                            WHERE
                                                I.id = insumos.id
                                            AND I.insumo_grupo_id = IG.id
                                        )
                                    AND L.deleted_at IS NULL
                                ) ,
                                0
                            ) + IFNULL(
                                (
                                    SELECT
                                        SUM(dias_prazo) prazo
                                    FROM
                                        workflow_alcadas
                                    WHERE
                                        EXISTS(
                                            SELECT
                                                1
                                            FROM
                                                workflow_usuarios
                                            WHERE
                                                workflow_alcada_id = workflow_alcadas.id
                                        )
                                ) ,
                                0
                            )
                        ) DAY))*1000 as start"),
                DB::raw("UNIX_TIMESTAMP(DATE_SUB(planejamentos.data, INTERVAL (
                                IFNULL(
                                    (
                                        SELECT
                                            SUM(L.dias_prazo_minimo) prazo
                                        FROM
                                            lembretes L
                                        JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    insumos I
                                                WHERE
                                                    I.id = insumos.id
                                                AND I.insumo_grupo_id = IG.id
                                            )
                                        AND L.deleted_at IS NULL
                                    ) ,
                                    0
                                ) + IFNULL(
                                    (
                                        SELECT
                                            SUM(dias_prazo) prazo
                                        FROM
                                            workflow_alcadas
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    workflow_usuarios
                                                WHERE
                                                    workflow_alcada_id = workflow_alcadas.id
                                            )
                                    ) ,
                                    0
                                )
                            ) DAY))*1000 as end"),
            ]);

        if ($request->from || $request->to) {
            if ($request->from) {
                $from = date('Y-m-d', $request->from / 1000);
                $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                                            IFNULL(
                                                (
                                                    SELECT
                                                        SUM(L.dias_prazo_minimo) prazo
                                                    FROM
                                                        lembretes L
                                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                insumos I
                                                            WHERE
                                                                I.id = insumos.id
                                                            AND I.insumo_grupo_id = IG.id
                                                        )
                                                    AND L.deleted_at IS NULL
                                                ) ,
                                                0
                                            ) + IFNULL(
                                                (
                                                    SELECT
                                                        SUM(dias_prazo) prazo
                                                    FROM
                                                        workflow_alcadas
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                workflow_usuarios
                                                            WHERE
                                                                workflow_alcada_id = workflow_alcadas.id
                                                        )
                                                ) ,
                                                0
                                            )
                                        ) DAY)'), '>=', $from);
            }
            if ($request->to) {
                $to = date('Y-m-d', $request->to / 1000);
                $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                                IFNULL(
                                    (
                                        SELECT
                                            SUM(L.dias_prazo_minimo) prazo
                                        FROM
                                            lembretes L
                                        JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    insumos I
                                                WHERE
                                                    I.id = insumos.id
                                                AND I.insumo_grupo_id = IG.id
                                            )
                                        AND L.deleted_at IS NULL
                                    ) ,
                                    0
                                ) + IFNULL(
                                    (
                                        SELECT
                                            SUM(dias_prazo) prazo
                                        FROM
                                            workflow_alcadas
                                        WHERE
                                            EXISTS(
                                                SELECT
                                                    1
                                                FROM
                                                    workflow_usuarios
                                                WHERE
                                                    workflow_alcada_id = workflow_alcadas.id
                                            )
                                    ) ,
                                    0
                                )
                            ) DAY)'), '<=', $to);
            }
        } else {
            $lembretes->where(DB::raw('DATE_SUB(planejamentos.data, INTERVAL (
                                            IFNULL(
                                                (
                                                    SELECT
                                                        SUM(L.dias_prazo_minimo) prazo
                                                    FROM
                                                        lembretes L
                                                    JOIN insumo_grupos IG ON IG.id = L.insumo_grupo_id
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                insumos I
                                                            WHERE
                                                                I.id = insumos.id
                                                            AND I.insumo_grupo_id = IG.id
                                                        )
                                                    AND L.deleted_at IS NULL
                                                ) ,
                                                0
                                            ) + IFNULL(
                                                (
                                                    SELECT
                                                        SUM(dias_prazo) prazo
                                                    FROM
                                                        workflow_alcadas
                                                    WHERE
                                                        EXISTS(
                                                            SELECT
                                                                1
                                                            FROM
                                                                workflow_usuarios
                                                            WHERE
                                                                workflow_alcada_id = workflow_alcadas.id
                                                        )
                                                ) ,
                                                0
                                            )
                                        ) DAY)'), '<=', DB::raw('CURRENT_DATE'));
        }

        if ($request->obra_id) {
            $lembretes->where('planejamentos.obra_id', $request->obra_id);
        }
        if ($request->planejamento_id) {
            $lembretes->where('planejamentos.id', $request->planejamento_id);
        }
        if ($request->insumo_grupo_id) {
            $lembretes->where('insumos.insumo_grupo_id', $request->insumo_grupo_id);
        }
        $lembretes->whereRaw('(
                                            SELECT
                                            1
                                        FROM
                                            planejamento_compras plc
                                        JOIN planejamentos P ON P.id = plc.planejamento_id
                                        LEFT JOIN ordem_de_compra_itens oci ON oci.insumo_id = plc.insumo_id
                                        AND oci.grupo_id = plc.grupo_id
                                        AND oci.subgrupo1_id = plc.subgrupo1_id
                                        AND oci.subgrupo2_id = plc.subgrupo2_id
                                        AND oci.subgrupo3_id = plc.subgrupo3_id
                                        AND oci.servico_id = plc.servico_id
                                        AND oci.obra_id = P.obra_id
                                        JOIN orcamentos orc ON orc.insumo_id = plc.insumo_id
                                        AND orc.grupo_id = plc.grupo_id
                                        AND orc.subgrupo1_id = plc.subgrupo1_id
                                        AND orc.subgrupo2_id = plc.subgrupo2_id
                                        AND orc.subgrupo3_id = plc.subgrupo3_id
                                        AND orc.servico_id = plc.servico_id
                                        AND orc.ativo = 1
                                        AND orc.obra_id = P.obra_id
                                        LEFT JOIN ordem_de_compras ocs ON ocs.id = oci.ordem_de_compra_id
                                        AND ocs.oc_status_id NOT IN(1 , 4 , 6)
                                        WHERE
                                            P.id = planejamentos.id
                                            AND plc.deleted_at IS NULL
                                            AND orc.qtd_total > 0
                                            AND IFNULL(oci.qtd , 0) < orc.qtd_total
                                        
                                        LIMIT 1
                                       ) IS NOT NULL ');
        $lembretes->distinct('id','obra','tarefa');
        $lembretes = $lembretes->groupBy(['id','obra','tarefa','title','class','url','inicio','start','end'])->get();

        return response()->json([
            'success' => true,
            'result' => $lembretes
        ]);
    }

    public function getPlanejamentosByObra(Request $request)
    {
        $planejamentos = Planejamento::where('obra_id', $request->obra_id)
            ->where('planejamentos.tarefa','LIKE', '%'.$request->q.'%')
            ->select([
                'planejamentos.id',
                'planejamentos.tarefa as text'
            ])
            ->where('planejamentos.resumo','Sim')
            ->groupBy('planejamentos.id','planejamentos.tarefa');
        return $planejamentos->paginate();
    }
}