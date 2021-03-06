@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Tipo de documentos fiscais
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body forp">
                <div class="row" style="padding-left: 20px">
                    @include('documento_tipos.show_fields')
                    <a href="{!! route('documentoTipos.index') !!}" class="btn btn-warning">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
