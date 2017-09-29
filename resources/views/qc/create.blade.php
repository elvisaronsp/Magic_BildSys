@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>Criar Q.C.</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'qc.store', 'files' => true]) !!}

                        @include('qc.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection