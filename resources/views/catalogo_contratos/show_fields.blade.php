<!-- Fornecedores Field -->
<div class="row">
    <div class="form-group col-sm-6">
        {!! Form::label('fornecedor_cod', 'Fornecedor:') !!}
        <div class="form-control">
            {{ $catalogoContrato->fornecedor->nome }}
            @if($catalogoContrato->fornecedor->faltaDados())
                <a href="{{ route('admin.fornecedores.edit', $catalogoContrato->fornecedor_id) }}"
                   title="Para poder gerar contratos automaticamente, todos os dados devem estar preenchidos:
                       {{ implode(',', $catalogoContrato->fornecedor->faltaQuaisDados())  }}"
                   data-toggle="tooltip" data-placement="top" class="btn btn-danger btn-xs pull-right">
                    <i class="fa fa-exclamation-triangle"></i>
                    Preencha todos os dados
                </a>
            @endif
        </div>
    </div>

    <div class="form-group col-sm-6">
        @if(strlen($catalogoContrato->minuta_assinada))
            {!! Form::label('Baixar Minuta já assinada:') !!}
            <a href="{{ Storage::url($catalogoContrato->minuta_assinada) }}" target="_blank"
               class="btn btn-info btn-md btn-flat btn-block">
                <i class="fa fa-download"></i> Baixar
            </a>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>Campos extras Minuta de Acordo</h4>
        <?php
        $contratoTemplateMinuta = \App\Models\ContratoTemplate::where('tipo','A')->first(); // busca o de Acordo
        if( strlen(trim($contratoTemplateMinuta->campos_extras)) ){
            $campos_extras = json_decode($contratoTemplateMinuta->campos_extras);
        }

        $valores_campos_extras_minutas = null;
        if(isset($catalogoContrato)){
            $valores_campos_extras_minutas = json_decode($catalogoContrato->campos_extras_minuta);
        }
        ?>
        @if($campos_extras)
            <table class="table table-condensed table-hovered table-striped table-bordered">
                <thead>
                <th width="40%">Campo</th>
                <th width="40%">Valor</th>
                <th width="20%">Tipo</th>
                </thead>
                <tbody>
                @foreach($campos_extras as $campo => $valor)
                    <?php
                    $v_tag = 'CAMPO_EXTRA_MINUTA['. str_replace(']','', str_replace('[','', $valor->tag )). ']' ;
                    $tag =  str_replace(']','', str_replace('[','', $valor->tag )) ;
                    ?>
                    <tr>
                        <td class="text-center">
                            <label for="{{ $v_tag }}">{{ $valor->nome }}</label>
                        </td>
                        <td style="max-width:300px;">
                            <p class="form-control">{{ isset($valores_campos_extras_minutas->$tag)?$valores_campos_extras_minutas->$tag:null }}</p>
                        </td>
                        <td class="text-center" >
                            <label for="{{ $v_tag }}" >{{ $valor->tipo }}</label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
    <div class="col-md-6">
        <h4>Campos extras Quando for gerado um Contrato Automático</h4>
        <?php
        $contratoTemplateContrato = \App\Models\ContratoTemplate::where('tipo','M')->first(); // busca o de Acordo
        if( strlen(trim($contratoTemplateContrato->campos_extras)) ){
            $campos_extras = json_decode($contratoTemplateContrato->campos_extras);
        }

        $valores_campos_extras_contratos = null;
        if(isset($catalogoContrato)){
            $valores_campos_extras_contratos = json_decode($catalogoContrato->campos_extras_contrato);
        }
        ?>
        @if($campos_extras)
            <table class="table table-condensed table-hovered table-striped table-bordered">
                <thead>
                <th width="40%">Campo</th>
                <th width="40%">Valor</th>
                <th width="20%">Tipo</th>
                </thead>
                <tbody>
                @foreach($campos_extras as $campo => $valor)
                    <?php
                    $v_tag = 'CAMPO_EXTRA_CONTRATO['. str_replace(']','', str_replace('[','', $valor->tag )). ']' ;
                    $tag =  str_replace(']','', str_replace('[','', $valor->tag )) ;
                    ?>
                    <tr>
                        <td class="text-center">
                            <label for="{{ $v_tag }}">{{ $valor->nome }}</label>
                        </td>
                        <td style="max-width:300px;">
                            <p class="form-control">{{ isset($valores_campos_extras_contratos->$tag)?$valores_campos_extras_contratos->$tag:null }}</p>
                        </td>
                        <td class="text-center" >
                            <label for="{{ $v_tag }}" >{{ $valor->tipo }}</label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>

<div class="col-sm-12">
    <h2 class="ml15">Regionais que estão permitidas neste acordo</h2>
    {{ Form::hidden('qtd_regionais',(!isset($catalogoContrato)?0:$catalogoContrato->regionais()->count()),['id'=>'qtd_regionais']) }}

    <ul class="list-group" id="regional_list">
        <?php
        $count_regionais = 0;
        ?>
        @foreach($catalogoContrato->regionais as $cc_regional)
            <li class="list-group-item" id="regional_list_{{ $cc_regional->id }}">
                <input type="hidden" name="regional[{{ $count_regionais++ }}]" value="{{ $cc_regional->regional_id }}">
                <i class="fa fa-building"></i>  {{ $cc_regional->regional->nome }}
                <span class="label label-default" style="background-color: {{$cc_regional->status->cor}}">{{ $cc_regional->status->nome }}</span>
            </li>
        @endforeach
    </ul>
</div>

<?php
$count_insumos = 0;
?>
<div>

    <div class="modal-header">
        <div class="col-md-12">
            <h2>Insumos</h2>
        </div>
    </div>

    @php
        $array_insumos = [];
        $botao_insumo_id = null;
    @endphp

    @if(count($catalogoContrato->contratoInsumos))
        @foreach ($catalogoContrato->contratoInsumos->sortByDesc('id')->groupBy('insumo_id') as $insumo)
            @foreach($insumo as $item)
                @php
                    $count_insumos = $item->id;
                    $podeEditar = false;
                    if($catalogoContrato->catalogo_contrato_status_id < 3){
                        // Se a data de inserção deste item for maior que a data de alteração para status Ativo, libera a edição
                        $podeEditar = true;
                    }
                @endphp
                @if(count($array_insumos))
                    <div class="col-md-6 border-separation pull-right" {{@isset(array_count_values($array_insumos)[$item->insumo_id]) ? 'style=display:none;' : 'style=margin-bottom:20px;'}}></div>
                    @if(@isset(array_count_values($array_insumos)[$item->insumo_id]) && $botao_insumo_id != $item->insumo_id)
                        @php
                            $botao_insumo_id = $item->insumo_id;
                        @endphp
                        <button class="btn btn-warning flat pull-right" type="button" onclick="mostrarReajustes('{{$item->insumo_id}}', 1)" id="btn_mostrar_ocultar_{{$item->insumo_id}}" title="Mostrar/Ocultar todos os reajustes" style="margin:-35px 30px 0 0;">
                            <i class="fa fa-plus" id="icon_mostrar_ocultar_{{$item->insumo_id}}"></i> Mostrar/Ocultar todos os reajustes
                        </button>
                    @endif
                @endif
                <div class="form-group col-md-12 bloco_insumos_id_{{$item->insumo_id}}" style="margin-top:-7px">

                    <div class="insumo col-md-12" {{in_array($item->insumo_id, $array_insumos) ? 'style=display:none;' : ''}}>
                        <label>Insumo:</label>
                        <div class="form-control overflowH">
                            {{ $item->insumo->codigo }} - {{ $item->insumo->nome }} - {{ $item->insumo->unidade_sigla }}
                        </div>
                    </div>

                    <div id="reajuste_{{$item->insumo_id}}"></div>

                    <div class="bloco {{in_array($item->insumo_id, $array_insumos) ? 'hidden form-group bloco_mostrar_reajustes_'.$item->insumo_id : ''}}">

                        <div class="col-md-12 {{in_array($item->insumo_id, $array_insumos) ? 'border-separation bloco_mostrar_reajustes_'.$item->insumo_id : ''}}" style="margin-bottom:20px;"></div>

                        <div class="col-md-3">
                            <label>Valor unitário:</label>
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                <div class="form-control overflowH">{{$item->valor_unitario}}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Pedido quantidade mínima:</label>
                            <div class="form-control overflowH">{{$item->pedido_minimo}}</div>
                        </div>
                        <div class="col-md-2">
                            <label>Pedido múltiplo de:</label>
                            <div class="form-control overflowH">{{$item->pedido_multiplo_de}}</div>
                        </div>
                        <div class="col-md-2">
                            <label>Período início:</label>
                            <div class="form-control overflowH">{{$item->periodo_inicio ? $item->periodo_inicio->format('d/m/Y') : null}}</div>
                        </div>
                        <div class="col-md-2">
                            <label>Período término:</label>
                            <div class="form-control overflowH">{{$item->periodo_termino ? $item->periodo_termino->format('d/m/Y') : null}}</div>
                        </div>
                        
                    </div>
                </div>
                <div class="textAtualiza {{in_array($item->insumo_id, $array_insumos) ? 'hidden bloco_mostrar_reajustes_'.$item->insumo_id : ''}}">
                    <p>
                        {{count($insumo) > 1 ? 'Alterado' : 'Criado'}} por {{$item->user ? $item->user->name : null}} em {{$item->created_at->format('d/m/Y H:i')}}
                    </p>
                </div>
                @php $array_insumos[] = $item->insumo_id; @endphp
            @endforeach
        @endforeach
    @endif

    @if(count($catalogoContrato->contratoInsumos))
        <div class="col-md-12 border-separation"></div>
    @endif
    <div id="insumos"></div>
</div>

@section('scripts')
    <script type="text/javascript">
        function mostrarReajustes(item, mostrar) {
            if(mostrar){
                $('.bloco_mostrar_reajustes_'+item).removeClass('hidden');
                $('#btn_mostrar_ocultar_'+item).attr('onclick', 'mostrarReajustes('+item+', 0)');
                $('#icon_mostrar_ocultar_'+item).attr('class', 'fa fa-minus');
            }else{
                $('.bloco_mostrar_reajustes_'+item).addClass('hidden');
                $('#btn_mostrar_ocultar_'+item).attr('onclick', 'mostrarReajustes('+item+', 1)');
                $('#icon_mostrar_ocultar_'+item).attr('class', 'fa fa-plus');
            }
        }
    </script>
@endsection