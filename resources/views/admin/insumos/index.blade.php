@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Insumos
        </h1>
    </section>

    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.insumos.table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
    <script src="{{ asset('js/insumo-activation.js') }}"></script>
@endsection
