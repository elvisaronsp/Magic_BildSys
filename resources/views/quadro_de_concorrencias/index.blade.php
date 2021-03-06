@extends('layouts.front')

@section('content')
    <style>
        .element-grafico{
            width: 100%;
            border: solid 1px #dddddd;
        }
        .element-head {
            text-align: center;
            color: #f5f5f5;
            padding: 10px 0px 10px 0px;
            background-color: #474747;
            font-family: Raleway;
            font-weight: bold;
        }
        .element-body{
            min-height: 300px;
            padding: 15px;
            background-color: white;
        }
    </style>
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <span>Quadro de concorrência</span>
            <a class="btn btn-primary btn-lg btn-flat pull-right" href="{!! url('/ordens-de-compra/insumos-aprovados') !!}">
                <i class="fa fa-plus"></i> Criar novo quadro de concorrência
            </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-warning quadro">
            <div class="box-body">
                    @include('quadro_de_concorrencias.table')
            </div>
        </div>
    </div>
@endsection
