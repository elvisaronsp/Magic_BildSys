@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Carteira de Q.C. Avulso
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.qc_avulso_carteiras.store', 'files' => true]) !!}

                        @include('admin.qc_avulso_carteiras.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection



