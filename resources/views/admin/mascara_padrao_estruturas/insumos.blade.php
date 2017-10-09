@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Máscara Padrão - {{(isset($mascaraPadrao) ? $mascaraPadrao->nome : $mascaraPadraoEstrutura->nome)}}
        </h1>
        <h1>
            <a class="btn btn-primary pull-right" style="margin-top: -30px;" href="{!! route('admin.mascara_padrao_insumos.index') !!}">
                Insumos relacionados
            </a>
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        {!! Form::open(['route' => 'admin.mascara_padrao_insumos.store', 'files' => true]) !!}
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="col-md-12">
                        {!! Form::label('mascara_padrao_estrutura_id', 'Máscara Padrão Estrutura:') !!}
                        {!! Form::select('mascara_padrao_estrutura_id', ['' => 'Escolha...']+$selectMascaraPadraoEstruturas, (isset($mascaraPadraoEstrutura) ? $mascaraPadraoEstrutura->id : null),
                            ['class' => 'form-control select2'])
                        !!}
                    </div>
                </div>
                <div class="content" style="padding-left: 20px">
                    <div class="col-md-12">
                        @include('admin.mascara_padrao_estruturas.table')
                    </div>

                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-md-12 text-right">
                <a type="button" href="#"
                   class="btn btn-default btn-flat btn-lg"><i class="fa fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-flat btn-lg btn-success">Salvar</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        function adicionarInsumo(id){
            if(!$('#mascara_padrao_estrutura_id').val()) {
                swal('Ops!','Escolha uma estrutura de máscara padrão.', 'info');
            }

            var coeficiente = $("input[name='coeficiente_"+id+"']").val();
            var indireto = $("input[name='indireto_"+id+"']").val();
            var mascara_padrao_estrutura_id = $('#mascara_padrao_estrutura_id').val();
            var _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                // rota
                url: "{{route('admin.mascara_padrao_insumos.store')}}",
                data: {
                    // variaveis
                    'id': id,
                    'coeficiente': coeficiente,
                    'indireto': indireto,
                    'mascara_padrao_estrutura_id': mascara_padrao_estrutura_id,
                    '_token': _token
                },
                type : "POST"
            }).done(function (retorno){
                // success
                window.LaravelDataTables["dataTableBuilder"].draw(false);
            }).fail(function (){
                // error
                swal('Ops...', 'Não foi possível salvar o insumo', 'error');
            });
        }
    </script>
@endsection