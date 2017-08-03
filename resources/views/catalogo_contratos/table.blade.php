{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'],true) !!}

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        function ativarDesativarCatalogo(id) {
            $.ajax({
                type: 'GET',
                url: 'catalogo-acordos/acao/ativar-desativar',
                data: {
                    id: id
                }
            }).done(function() {
                LaravelDataTables.dataTableBuilder.draw();
            });
        }
    </script>
@endsection