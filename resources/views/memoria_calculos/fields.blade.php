<style type="text/css">
    .fa-th-large {
        cursor: ns-resize;
    }

    .table-bordered td {
        border: 1px solid #5a5555 !important;
    }
</style>

<!-- Nome Field -->
<div class="form-group col-sm-2">
    {!! Form::label('obra_id', 'Obra:') !!}
    {!! Form::select('obra_id',$obras, null, ['class' => 'form-control select2','required'=>'required']) !!}
</div>
<!-- Nome Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Modo Field -->
<div class="form-group col-sm-2">
    {!! Form::label('modo', 'Modo:') !!}
    {!! Form::select('modo',['T'=>'Torre','C'=>'Cartela','U'=>'Unidade'], null,
        ['class' => 'form-control select2', 'required'=>'required', 'onChange'=>'mudaModo(this.value);']) !!}
</div>

<!-- Padrão Field -->
<div class="form-group col-sm-2">
    {!! Form::label('padrao', 'Padrão:') !!}
    <div class="form-control">
        {!! Form::checkbox('padrao',1, null,['id'=>'padrao']) !!}
    </div>

</div>
<div class="form-group col-sm-2">
    {!! Form::label('bloco', 'Blocos:') !!}
    <div>
        <button type="button" id="btn_adicionar_bloco" onclick="adicionaBloco();"
                class="btn btn-flat btn-primary btn-block" disabled="disabled">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>
<div>
    <div class="col-md-6">
        <ul class="list-group" id="blocos">
            @if(isset($memoriaCalculo))
                <?php
                    $countTrechos = 0;
                    if ($memoriaCalculo->modo == 'T') {
                        $nomeEstrutura = 'Estrutura';
                        $nomePavimento = 'Pavimento';
                        $nomeTrecho = 'Trecho';
                    } else {
                        $nomeEstrutura = 'Bloco';
                        $nomePavimento = 'Linha';
                        $nomeTrecho = 'Coluna';
                    }
                ?>
                @foreach($blocos as $indexBloco => $bloco)
                    <li class="list-group-item estruturaClass" bloco="{{ $indexBloco }}" id="bloco_list_item_{{ $indexBloco }}">
                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-sm-8"><i class="fa fa-th-large"></i> &nbsp; {{ $nomeEstrutura }}:
                                {!! Form::select('estrutura_bloco['. $indexBloco .']',
                                    \App\Models\NomeclaturaMapa::where('tipo',1)
                                    ->where('apenas_cartela',($memoriaCalculo->tipo=='C'?'1':'0') )
                                    ->where('apenas_unidade',($memoriaCalculo->tipo=='U'?'1':'0') )
                                    ->pluck('nome','id')->toArray() ,
                                    $bloco['objId'], ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'estrutura_bloco_'.$indexBloco] ) !!}
                                {!! Form::hidden('estrutura_bloco_ordem['. $indexBloco .']',$bloco['ordem']) !!}
                            </div>
                            <div class="col-sm-4" style="min-height: 54px; padding-top: 20px">
                                <button type="button" onclick="adicionaPavimento({{ $indexBloco }})" class="btn btn-flat btn-xs btn-info"><i
                                            class="fa fa-plus" ></i> {{ $nomePavimento }}
                                </button>
                                <button type="button" onclick="removeBloco({{ $indexBloco }})" title="Remover" class="btn btn-flat btn-xs btn-danger">
                                    <i class="fa fa-times" ></i></button>
                            </div>
                        </div>
                        <div style="clear: both">
                            <ul class="list-group pavBlocos" id="pavimentos_{{ $indexBloco }}">
                                @if( count($bloco['itens']) )
                                    @foreach($bloco['itens'] as $indexPavimento => $pavimento)
                                        <li class="list-group-item pavimentosClass{{ $indexBloco }}" pavimento="{{ $indexPavimento }}" id="linha_{{ $indexBloco }}_{{ $indexPavimento }}">
                                            <div class="row" style="margin-bottom: 10px">
                                                <div class="col-sm-8">
                                                    <b class="fa fa-th-large"></b> &nbsp; {{ $nomePavimento }}:
                                                    {!! Form::select('pavimentos['. $indexBloco .']['.$indexPavimento.']',
                                                        \App\Models\NomeclaturaMapa::where('tipo',2)
                                                        ->where('apenas_cartela',($memoriaCalculo->tipo=='C'?'1':'0') )
                                                        ->where('apenas_unidade',($memoriaCalculo->tipo=='U'?'1':'0') )
                                                        ->pluck('nome','id')->toArray() ,
                                                        $pavimento['objId'], ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'pavimentos_'.$indexBloco .'_'. $indexPavimento ] ) !!}
                                                    {!! Form::hidden('pavimento_bloco_ordem['.$indexBloco.']['.$indexPavimento.']',$pavimento['ordem']) !!}
                                                </div>
                                                <div class="col-sm-4" style="min-height: 54px; padding-top: 20px">
                                                    <button type="button" onclick="adicionaTrecho({{ $indexBloco }},{{ $indexPavimento }})"
                                                            class="btn btn-flat btn-xs btn-warning"><i class="fa fa-plus"></i> {{ $nomeTrecho }}
                                                    </button>
                                                    <button type="button" onclick="removeLinha({{ $indexBloco }},{{ $indexPavimento }})" title="Remover"
                                                            class="btn btn-flat btn-xs btn-danger"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div style="clear: both">
                                                <ul class="list-group trechoBlocos" id="trechos_{{ $indexBloco }}_{{ $indexPavimento }}">
                                                    @if( count($pavimento['itens']) )
                                                        @foreach($pavimento['itens'] as $indexTrecho => $trecho)
                                                            <?php
                                                                $countTrechos++;
                                                            ?>
                                                            <li class="list-group-item trechoClass{{ $indexBloco }}_{{ $indexPavimento }}" trecho="{{ $indexTrecho }}"
                                                                id="blocoTrecho_{{ $indexBloco }}_{{ $indexPavimento }}_{{ $indexTrecho }}">
                                                                <div class="input-group">
                                                                    <strong class="input-group-addon"
                                                                                               id="trecho{{ $indexBloco }}_{{ $indexPavimento }}_{{ $indexTrecho }}">{{ $nomeTrecho }}
                                                                    </strong>
                                                                    {!! Form::select('trecho['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',
                                                                        \App\Models\NomeclaturaMapa::where('tipo',3)
                                                                        ->where('apenas_cartela',($memoriaCalculo->tipo=='C'?'1':'0') )
                                                                        ->where('apenas_unidade',($memoriaCalculo->tipo=='U'?'1':'0') )
                                                                        ->pluck('nome','id')->toArray() ,
                                                                        $trecho['objId'],
                                                                        ['class'=>'form-control select2','onchange'=>'atualizaVisual();', 'id'=>'trecho_' .$indexBloco .'_'. $indexPavimento . '_'. $indexTrecho] ) !!}
                                                                    {!! Form::hidden('trecho_id['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']',$trecho['blocoId']) !!}

                                                                    <input type="hidden" name="trecho_bloco_ordem{{ '['.$indexBloco.']['.$indexPavimento.']['.$indexTrecho.']' }}"
                                                                           id="trecho_bloco_ordem_{{ $indexBloco.'_'.$indexPavimento.'_'.$indexTrecho }}" value="">
                                                                        <span class="input-group-btn">
                                                                            <button
                                                                                type="button" onclick="removeTrecho({{ $indexBloco.','.$indexPavimento.','.$indexTrecho }})" title="Remover"
                                                                                class="btn btn-flat btn-xs btn-danger"><i
                                                                                    class="fa fa-times"></i> </button>
                                                                        </span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>


    <div class="col-md-6" id="visual">

    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('memoriaCalculos.index') !!}" class="btn btn-default"><i
                class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}</a>
</div>
@section('scripts')
    <script type="text/javascript">
        var estruturasObjs = [];
        var pavimentosObjs = [];
        var trechosObjs = [];

        var estruturas = '';
        var pavimentos = '';
        var trechos = '';
        var nomeEstrutura = '';
        var nomePavimento = '';
        var nomeTrecho = '';
        var blocos = 0;
        var pavimentosCount = 0;
        var trechosCount = 0;
        $(function () {
            // ready
            @if(isset($memoriaCalculo))
                buscaNomeclaturas('{{ $memoriaCalculo->modo }}');
            @else
                buscaNomeclaturas('T');
            @endif

            sortable('#blocos', {handle: 'i'});

            sortable('#blocos')[0].addEventListener('sortstop', function (e) {
                atualizaVisual();
            });

            @if(isset($memoriaCalculo))
                blocos = {{ $indexBloco + 1 }};
                pavimentosCount = {{ $indexPavimento + 1 }};
                trechosCount = {{ $countTrechos + 1 }};

                sortable('.pavBlocos', {handle: 'b'});

                sortable('.pavBlocos')[0].addEventListener('sortstop', function (e) {
                    atualizaVisual();
                });

                sortable('.trechoBlocos', {handle: 'strong'});

                sortable('.trechoBlocos')[0].addEventListener('sortstop', function (e) {
                    atualizaVisual();
                });
            @endif

        });

        function mudaModo(valor) {
            $('#blocos').html('');
            buscaNomeclaturas(valor);
        }

        function atualizaVisual() {
            arrayVisual = [];
            $('.estruturaClass').each(function (idx) {
                k = $(this).attr('bloco');
                $('#estrutura_bloco_ordem_' + k).val(idx);

                nome = $("#estrutura_bloco_" + k + " option:selected").text();

                idx = $(this).parent().children().index(this);

                item = {
                    id: idx,
                    nome: nome,
                    objId: $("#estrutura_bloco_" + k + " option:selected").val(),
                    itens: [],
                    ordem: idx
                };
                arrayPavimentosOrdenada = [];
                $('.pavimentosClass'+k).each(function (idxPav) {

                    idxPav2 = $(this).parent().children().index(this);
                    p = $(this).attr('pavimento');

                    $('#pavimento_bloco_ordem_' + k + '_' + p).val(idxPav);
                    nomePav = $("#pavimentos_" + k + "_" + p + " option:selected").text();

                    pavimentoItem = {
                        id: idxPav,
                        nome: nomePav,
                        objId: $("#pavimentos_" + k + "_" + p + " option:selected").val(),
                        itens: [],
                        ordem: idxPav
                    }

                    arrayTrechos = [];
                    $('.trechoClass' + k + '_' + p).each(function (idxTrecho) {

                        idxTrecho = $(this).parent().children().index(this);

                        t = $(this).attr('trecho');

                        nomeTrech = $("#trecho_" + k + "_" + p + "_" + t + " option:selected").text();

                        $('#trecho_bloco_ordem_' + k + '_' + p + '_' + t).val(idxTrecho);

                        trechoItem = {
                            id: idxTrecho,
                            nome: nomeTrech,
                            objId: $("#trecho_" + k + "_" + p + "_" + t + " option:selected").val(),
                            ordem: idxTrecho
                        }

                        arrayTrechos[idxTrecho] = trechoItem;

                    });

                    pavimentoItem.itens = arrayTrechos;

                    arrayPavimentosOrdenada[idxPav] = pavimentoItem;
                });
                item.itens = arrayPavimentosOrdenada;

                arrayVisual[idx] = item;
            });


            // -- Preenche o visual
            visualHTML = '';
            arrayVisual.forEach(function (item, index) {
                objDBEstrutura = estruturasObjs[item.objId];
                largura = 100;
                if (objDBEstrutura != undefined) {
                    largura = objDBEstrutura.largura_visual;
                }
                pavimentosDestaEstrutura = '';
                if (item.itens.length == 0) {
                    pavimentosDestaEstrutura = '   <tr>' +
                            '       <td colspan="2"> &nbsp; </td>' +
                            '   </tr>';
                } else {
                    item.itens.forEach(function (linhaPavimento, indicePav) {
                        objDBEstruturaPav = estruturasObjs[linhaPavimento.objId];
                        larguraPav = 100;
                        if (objDBEstruturaPav != undefined) {
                            larguraPav = objDBEstruturaPav.largura_visual;
                        }
                        trechosDestePavimento = '';
                        if (linhaPavimento.itens.length == 0) {
                            trechosDestePavimento = '<table  style="width: ' + larguraPav + '%; margin:0px auto; min-height: 31px;"><tr> <td> &nbsp;</td> </tr></table>';
                        } else {
                            trechosTD = '';
                            linhaPavimento.itens.forEach(function (trechoPav, indiceTrec) {
                                objDBEstruturaTrecho = estruturasObjs[trechoPav.objId];
                                larguraTrecho = 100;
                                if (objDBEstruturaTrecho != undefined) {
                                    larguraTrecho = objDBEstruturaTrecho.largura_visual;
                                }
                                trechosTD += '<td>&nbsp;' + trechoPav.nome + '&nbsp;</td>';
                            });
                            trechosDestePavimento = '<table class="table-bordered" style="width: ' + larguraPav + '%; margin:0px auto;min-height: 31px;"><tr> ' + trechosTD + ' </tr></table>';
                        }

                        pavimentosDestaEstrutura += '   <tr><td class="warning" width="15%">' + linhaPavimento.nome + '</td> ' +
                                ' <td style="padding: 0px !important;"> ' + trechosDestePavimento + '</td> ' +
                                ' </tr>';
                    });
                }

                visualHTML += '<div class="row">' +
                        '<div class="col-sm-12 text-left">' + item.nome + '</div> ' +
                        '<div class="col-sm-12"> ' +
                        ' <table class="table table-condensed" style="width: ' + largura + '%; margin:5px auto;"> ' +
                        pavimentosDestaEstrutura +
                        ' </table> ' +
                        '</div> ' +
                        '</div>';
            });
            $('#visual').html(visualHTML);

        }

        function removeBloco(qual) {
            $('#bloco_list_item_' + qual).remove();
            atualizaVisual();
        }

        function removeLinha(bloco, pavimento) {
            $('#linha_' + bloco + '_' + pavimento).remove();
            atualizaVisual();
        }

        function removeTrecho(bloco, pavimento, trecho) {
            $('#blocoTrecho_' + trecho).remove();
            atualizaVisual();
        }

        function adicionaBloco() {
            blocos++;
            blocoHTML = '' +
                    '<li class="list-group-item estruturaClass" bloco="' + blocos + '" id="bloco_list_item_' + blocos + '">' +
                    '<div class="row" style="margin-bottom: 10px">' +
                    '<div class="col-sm-8">' +
                    '<i class="fa fa-th-large"></i> &nbsp; ' +
                    nomeEstrutura + ':' +
                    '<select class="form-control select2" id="estrutura_bloco_' + blocos + '" ' +
                    ' onchange="atualizaVisual();" name="estrutura_bloco[' + blocos + ']">' +
                    estruturas +
                    '</select><input type="hidden" name="estrutura_bloco_ordem[' + blocos + ']" id="estrutura_bloco_ordem_' + blocos + '" value="">' +
                    '</div>' +
                    '<div class="col-sm-4" style="min-height: 54px; padding-top: 20px">' +
                    '<button type="button" onclick="adicionaPavimento(' + blocos + ')" class="btn btn-flat btn-xs btn-info">' +
                    '<i class="fa fa-plus"></i> ' + nomePavimento +
                    '</button>' +
                    '<button type="button" onclick="removeBloco(' + blocos + ')" title="Remover" class="btn btn-flat btn-xs btn-danger">' +
                    '<i class="fa fa-times"></i> ' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '<div style="clear: both">' +
                    '<ul class="list-group pavBlocos" id="pavimentos_' + blocos + '">' +
                    '</ul>' +
                    '</div>' +
                    '</li>';
            $('#blocos').prepend(blocoHTML);
            $('#estrutura_bloco_' + blocos).select2({
                theme: 'bootstrap',
                placeholder: "-",
                language: "pt-BR",
                allowClear: true
            });

//            sortable('#blocos', 'reload');
            atualizaVisual();
        }


        function adicionaPavimento(bloco) {
            pavimentosCount++;

            pavimentoHTML = '' +
                    '<li class="list-group-item pavimentosClass' + bloco + '" pavimento="' + pavimentosCount + '" id="linha_' + bloco + '_' + pavimentosCount + '">' +
                    '<div class="row" style="margin-bottom: 10px">' +
                    '<div class="col-sm-8">' +
                    '<b class="fa fa-th-large"></b> &nbsp; ' +
                    nomePavimento + ':' +
                    '<select class="form-control select2" id="pavimentos_' + bloco + '_' + pavimentosCount + '" ' +
                    ' onchange="atualizaVisual();" name="pavimentos[' + bloco + '][' + pavimentosCount + ']">' +
                    pavimentos +
                    '</select> ' +
                    ' <input type="hidden" name="pavimento_bloco_ordem[' + bloco + '][' + pavimentosCount + ']" id="pavimento_bloco_ordem_' + bloco + '_' + pavimentosCount + '" value="">' +
                    '</div>' +
                    '<div class="col-sm-4" style="min-height: 54px; padding-top: 20px">' +
                    '<button type="button" onclick="adicionaTrecho(' + bloco + ',' + pavimentosCount + ')" class="btn btn-flat btn-xs btn-warning">' +
                    '<i class="fa fa-plus"></i> ' + nomeTrecho +
                    '</button>' +
                    '<button type="button" onclick="removeLinha(' + blocos + ',' + pavimentosCount + ')" title="Remover" class="btn btn-flat btn-xs btn-danger">' +
                    '<i class="fa fa-times"></i> ' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '<div style="clear: both">' +
                    '<ul class="list-group trechoBlocos" id="trechos_' + bloco + '_' + pavimentosCount + '">' +
                    '</ul>' +
                    '</div>' +
                    '</li>';
            $('#pavimentos_' + bloco).append(pavimentoHTML);
            $('#pavimentos_' + bloco + '_' + pavimentosCount).select2({
                theme: 'bootstrap',
                placeholder: "-",
                language: "pt-BR",
                allowClear: true
            });
//            sortable('#pavimentos_' + bloco, 'reload');

        }


        function adicionaTrecho(bloco, pavimento) {
            trechosCount++;

            trechoHTML = '' +
                    '<li class="list-group-item trechoClass' + bloco + '_' + pavimento + '" trecho="' + trechosCount + '" id="blocoTrecho_' + trechosCount + '">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon" id="trecho' + trechosCount + '">' + nomeTrecho + '</span>' +
                    '<select class="form-control select2" onchange="atualizaVisual()"  ' +
                    ' name="trecho[' + bloco + '][' + pavimento + '][' + trechosCount + ']" id="trecho_' + bloco + '_' + pavimento + '_' + trechosCount + '">' +
                    trechos +
                    '</select>' +
                    ' <input type="hidden" name="trecho_bloco_ordem[' + bloco + '][' + pavimento + '][' + trechosCount + ']" ' +
                    ' id="trecho_bloco_ordem_' + bloco + '_' + pavimento + '_' + trechosCount + '" value="">' +
                    '<span class="input-group-btn">' +
                    '<button type="button" onclick="removeTrecho(' + blocos + ',' + pavimento + ',' + trechosCount + ')" title="Remover" class="btn btn-flat btn-xs btn-danger">' +
                    '<i class="fa fa-times"></i> ' +
                    '</button>' + '</span>' +
                    '</div>' +
                    '</li>';
            $('#trechos_' + bloco + '_' + pavimento).append(trechoHTML);
            $('#trecho_' + trechosCount).select2({
                theme: 'bootstrap',
                placeholder: "-",
                language: "pt-BR",
                allowClear: true
            });
//            sortable('#trechos_' + bloco + '_' + pavimento, 'reload');
        }


        function buscaNomeclaturas(valor) {
            if (valor == 'T') {
                nomeEstrutura = 'Estrutura';
                nomePavimento = 'Pavimento';
                nomeTrecho = 'Trecho';
            } else {
                nomeEstrutura = 'Bloco';
                nomePavimento = 'Linha';
                nomeTrecho = 'Coluna';
            }
            estruturasObjs = [];
            pavimentosObjs = [];
            trechosObjs = [];

            $('#btn_adicionar_bloco').attr('disabled', true);
            estruturas = '<option value="" selected="selected">Escolha</option>';
            pavimentos = '<option value="" selected="selected">Escolha</option>';
            trechos = '<option value="" selected="selected">Escolha</option>';
            $.ajax('/nomeclatura-mapas/json?modo=' + valor)
                    .fail(function (retorno) {
                        swal({title: 'Erro na solicitação', type: 'error'}, function () {
                            document.location.reload();
                        });
                    })
                    .done(function (retorno) {
                        $.each(retorno, function (index, nomeclatura) {
                            if (nomeclatura.tipo == 1) {
                                estruturas += '<option value="' + nomeclatura.id + '">' + nomeclatura.nome + '</option>';
                                estruturasObjs[nomeclatura.id] = nomeclatura;
                            }
                            if (nomeclatura.tipo == 2) {
                                pavimentos += '<option value="' + nomeclatura.id + '">' + nomeclatura.nome + '</option>';
                                pavimentosObjs[nomeclatura.id] = nomeclatura;
                            }
                            if (nomeclatura.tipo == 3) {
                                trechos += '<option value="' + nomeclatura.id + '">' + nomeclatura.nome + '</option>';
                                trechosObjs[nomeclatura.id] = nomeclatura;
                            }
                        });
                        $('#btn_adicionar_bloco').attr('disabled', false);
                        atualizaVisual();
                    });
        }
    </script>
@stop