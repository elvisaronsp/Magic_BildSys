@extends('layouts.app')

@section('content')
    <section class="content-header">
        <ol class="breadcrumb" style="right: 80px">
            <li ><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><a href="/admin/manage"> Controle de Acesso</a></li>
        </ol>
        <h1>
            Usuários
            <a class="btn btn-primary pull-right"  href="{!! route('users.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.manage.users.table')
            </div>
        </div>
    </div>
@endsection