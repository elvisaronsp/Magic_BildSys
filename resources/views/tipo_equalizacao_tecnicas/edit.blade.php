@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Equalização técnica
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body forp">
               <div class="row">
                   {!! Form::model($tipoEqualizacaoTecnica, ['route' => ['tipoEqualizacaoTecnicas.update', $tipoEqualizacaoTecnica->id], 'method' => 'patch', 'files'=>true]) !!}

                        @include('tipo_equalizacao_tecnicas.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection