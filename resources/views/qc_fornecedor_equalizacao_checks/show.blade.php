@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Qc Fornecedor Equalizacao Check
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('qc_fornecedor_equalizacao_checks.show_fields')
                    <a href="{!! route('qcFornecedorEqualizacaoChecks.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection