{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover']) !!}

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@stop