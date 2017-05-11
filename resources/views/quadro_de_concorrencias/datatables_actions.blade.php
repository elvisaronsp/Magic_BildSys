{!! Form::open(['route' => ['quadroDeConcorrencias.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('quadroDeConcorrencias.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @shield('quadroDeConcorrencias.edit')
        @if($qc_status_id!=6)
        <a href="{{ route('quadroDeConcorrencias.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        @endif
        @if($qc_status_id==5)
            <button type="button" class="btn btn-xs btn-success" onclick="abrirConcorrencia({{$id}});" title="Abrir concorrência">
                <i class="fa fa-play-circle-o" aria-hidden="true"></i>
            </button>
        @endif
        @if($qc_status_id!=6)
        <button type="button" class="btn btn-xs btn-default" onclick="cancelarQC({{$id}});" title="Cancelar Quadro de Concorrência">
            <i class="glyphicon glyphicon-remove"></i>
        </button>
        @endif
    @endshield
    @shield('quadroDeConcorrencias.informar_valor')
        @if($qc_status_id == 7)
        <a href="{{ route('quadroDeConcorrencia.informar-valor', $id) }}" class="btn btn-xs btn-info" title="Informar valores">
            <i class="glyphicon glyphicon-usd"></i>
        </a>
        @endif
    @endshield
    @shield('quadroDeConcorrencias.delete')
        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endshield

</div>
{!! Form::close() !!}