<?php

namespace App\DataTables;

use App\Models\ConfiguracaoEstatica;
use Form;
use Yajra\Datatables\Services\DataTable;

class ConfiguracaoEstaticaDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'configuracao_estaticas.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $configuracaoEstaticas = ConfiguracaoEstatica::query();

        return $this->applyScopes($configuracaoEstaticas);
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
//            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'initComplete' => 'function () {
                    max = this.api().columns().count();
                    this.api().columns().every(function (col) {
                        if((col+2)<max){
                            var column = this;
                            var input = document.createElement("input");
                            $(input).attr(\'placeholder\',\'Filtrar...\');
                            $(input).addClass(\'form-control\');
                            $(input).css(\'width\',\'100%\');
                            $(input).appendTo($(column.footer()).empty())
                            .on(\'change\', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                        }
                    });
                }' ,
                'dom' => 'Bfrtip',
                'scrollX' => false,
                'language'=> [
                    "url"=> asset("vendor/datatables/Portuguese-Brasil.json")
                ],
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
                             'pdf',
                         ],
                    ],
                    'colvis'
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'nome' => ['name' => 'chave', 'data' => 'chave'],
            'descrição' => ['name' => 'valor', 'data' => 'valor'],
            'action' => ['title' => 'Ações', 'printable' => false, 'exportable' => false, 'searchable' => false, 'orderable' => false, 'width'=>'10%']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'configuracaoEstaticas';
    }
}
