@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1><button type="button" class="btn btn-link" onclick="history.go(-1);"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>Medição Física</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.medicao_fisicas.store', 'files' => true]) !!}

                        @include('admin.medicao_fisicas.create_fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection



