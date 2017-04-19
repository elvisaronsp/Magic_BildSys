@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Planejamentos</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.planejamentos.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
            <a class="btn btn-warning pull-right" style="margin-top: -10px;margin-bottom: 5px; margin-right: 20px;" href="{!! route('admin.planejamentos.indexImport') !!}">
                Importar Planejamentos
            </a>
        </h1>

    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.planejamentos.table')
            </div>
        </div>
    </div>
@endsection
