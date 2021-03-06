@extends('layouts.front')
@section('styles')
    <style type="text/css">

        #totalInsumos h5{
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }
        #totalInsumos h4{
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }
        #totalInsumos{
            margin-bottom: 20px;
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
                    <span class="pull-left title">
                        <h3>
                            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                             <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <span>Ordem de compra - Análise do orçamento - Nível serviço</span>
                        </h3>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <h6>Dados Informativos</h6>
        <div class="js-datatable-filter-form">
            <input type="hidden" name="itens_selecionados" id="itens_selecionados">
        </div>
        <div class="row">
            <div class="col-md-2 form-group">
                {!! Form::label('codigo', 'Código do serviço') !!}
                @php
                    $tooltip = $servico->grupo->grupo->grupo->grupo->codigo . ' - ' . $servico->grupo->grupo->grupo->grupo->nome . '<br>' .
                               $servico->grupo->grupo->grupo->codigo . ' - ' . $servico->grupo->grupo->grupo->nome . '<br>' .
                               $servico->grupo->grupo->codigo . ' - ' . $servico->grupo->grupo->nome . '<br>' .
                               $servico->grupo->codigo . ' - ' . $servico->grupo->nome . '<br>' .
                               $servico->codigo . ' - ' . $servico->nome . '<br>';
                @endphp
                <p class="form-control input-lg highlight text-center"
                   data-toggle="tooltip" data-placement="top" data-html="true"
                   title="{{$tooltip}}">
                    {!! $servico->codigo !!}
                </p>
            </div>

            <div class="col-md-10 form-group">
                {!! Form::label('servico', 'Serviço') !!}
                <p class="form-control input-lg"
                   data-toggle="tooltip" data-placement="top" data-html="true"
                   title="{{$tooltip}}">
                    {!! $servico->nome !!}
                </p>
            </div>
        </div>
        <hr>
        <div class="row" id="totalInsumos">
            <div class="col-md-2 text-right borda-direita">
                <h5>Valor previsto no orçamento</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="valor_previsto"></span>
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Até o momento em todos os itens desta O.C.">
                <h5>Valor comprometido realizado</h5>
                <h4>
                    <small class="pull-left">R$</small>0,00
                    {{---  TO DO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
                    {{--                    {{ number_format($realizado,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Nos itens desta O.C.">
                <h5>Valor comprometido à gastar</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="valor_comprometido_a_gastar"></span>
                    {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
                <h5>SALDO DE ORÇAMENTO</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="saldo_orcamento"></span>
                    {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
                    {{--{{ number_format($saldo,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita">
                <h5>VALOR DA OC</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="valor_oc"></span>
                </h4>
            </div>
            <div class="col-md-2 text-right">
                <h5>SALDO DISPONÍVEL APÓS O.C</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="saldo_disponivel"></span>
                </h4>
            </div>
        </div>

        <div class="content">
            @include('ordem_de_compras.obras-insumos-table')
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        var itens_selecionados = [];

        function recalcularAnaliseServico() {
            startLoading();
            var valor_previsto = 0;
            var valor_comprometido_a_gastar = 0;
            var saldo_orcamento = 0;
            var valor_oc = 0;
            var saldo_disponivel = 0;
            var tem_checked = false;

            $('.detalhes_servicos_itens').each(function (index, value) {
                if($(value).prop('checked')) {
                    tem_checked = true;
                    valor_previsto += parseFloat($(value).attr('valor_previsto'));
                    valor_comprometido_a_gastar += parseFloat($(value).attr('valor_comprometido_a_gastar'));
                    saldo_orcamento += parseFloat($(value).attr('saldo_orcamento'));
                    valor_oc += parseFloat($(value).attr('valor_oc'));
                    saldo_disponivel += parseFloat($(value).attr('saldo_disponivel'));

                    itens_selecionados.push($(value).attr('id'));
                } else {
                    Array.prototype.remove = function() {
                        var what, a = arguments, L = a.length, ax;
                        while (L && this.length) {
                            what = a[--L];
                            while ((ax = this.indexOf(what)) !== -1) {
                                this.splice(ax, 1);
                            }
                        }
                        return this;
                    };

                    itens_selecionados.remove($(value).attr('id'));
                }
                if(index+1 === $('.detalhes_servicos_itens').length) {
                    if(tem_checked) {
                        $('#valor_previsto').text(floatToMoney(valor_previsto, ''));
                        $('#valor_comprometido_a_gastar').text(floatToMoney(valor_comprometido_a_gastar, ''));
                        $('#saldo_orcamento').text(floatToMoney(saldo_orcamento, ''));
                        $('#valor_oc').text(floatToMoney(valor_oc, ''));
                        $('#saldo_disponivel').text(floatToMoney(saldo_disponivel, ''));

                        $('#dataTableBuilder').on('preXhr.dt', function ( e, settings, data ) {
                            startLoading();
                            $('.js-datatable-filter-form :input').each(function () {
                                data[$(this).prop('name')] = itens_selecionados;
                            });

                            setTimeout(function () {
                                $.each(itens_selecionados, function( index, value ) {
                                    $('#'+value).attr('checked', true);
                                });

                                stopLoading();
                            }, 2000);
                        });

                    } else {
                        @php
                            $calculos = \App\Repositories\OrdemDeCompraRepository::calculosDetalhesServicos(Request::segment(3), Request::segment(4), \Illuminate\Support\Facades\Input::get('oc_id'));
                        @endphp

                        $('#valor_previsto').text('{{float_to_money($calculos['valor_previsto'], '')}}');
                        $('#valor_comprometido_a_gastar').text('{{float_to_money($calculos['valor_comprometido_a_gastar'], '')}}');
                        $('#saldo_orcamento').text('{{float_to_money($calculos['saldo_orcamento'], '')}}');
                        $('#valor_oc').text('{{float_to_money($calculos['valor_oc'], '')}}');
                        $('#saldo_disponivel').text('{{float_to_money($calculos['saldo_disponivel'], '')}}');
                    }
                }
            });

            window.LaravelDataTables["dataTableBuilder"].draw();

            setTimeout(function () {
                $.each(itens_selecionados, function( index, value ) {
                    $('#'+value).attr('checked', true);
                });

                stopLoading();
            }, 2000);
        }
    </script>
@endsection