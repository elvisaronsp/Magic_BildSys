<?php

namespace App\DataTables\Admin;

use App\Models\Insumo;
use Yajra\Datatables\Services\DataTable;

class MascaraPadraoRelacionarInsumoDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'admin.mascara_padrao_estruturas.relacionar_insumos_datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Insumo::query()
            ->select([
                'id',
                'nome',
                'codigo'
            ])
            ->where('active', 1);

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax('')
            ->parameters([
                'responsive'=> 'true',
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+1)<max){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }else{
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'type\',\'checkbox\');
                            $(input).attr(\'id\',\'checkUncheckAll\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                $(\'.item_checks\').prop("checked", $(this).prop("checked"));
                            });
                            $(column.footer()).addClass(\'text-center\');
                        }
                    });
                }' ,
//                "lengthChange"=> true,
                "pageLength"=> 25,
                'dom' => 'Bfrltip',
                'scrollX' => false,
                'language'=> [
                    "url"=> "/vendor/datatables/Portuguese-Brasil.json"
                ],
                'buttons' => [
                    'reset',
                    'reload',
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'Código' => ['name' => 'codigo', 'data' => 'codigo'],
            'nome' => ['name' => 'nome', 'data' => 'nome'],
            'action' => ['name' => 'Ações', 'title' => 'Selecionar', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10px', 'class' => 'all'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'mascarapadraorelacionarinsumodatatables_' . time();
    }
}
