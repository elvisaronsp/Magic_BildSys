@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Retroalimentacao Obra
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($retroalimentacaoObra, ['route' => ['retroalimentacaoObras.update', $retroalimentacaoObra->id], 'method' => 'patch']) !!}

                        @include('retroalimentacao_obras.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection