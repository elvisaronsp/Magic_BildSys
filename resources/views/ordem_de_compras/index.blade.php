@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Ordem De Compras</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('ordemDeCompras.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('ordem_de_compras.table')
            </div>
        </div>
    </div>
@endsection

