@if(!$espelho)
    <hr>
    <div class="row total-header">
        <div class="col-xs-3 text-right borda-direita">
            <h5>Valor Inicial</h5>
            <h4>
                <small class="pull-left">R$</small>
                {{ float_to_money($contrato->valor_total_inicial, '') }}
            </h4>
        </div>
        <div class="col-xs-3 text-right borda-direita">
            <h5>Valor Atual</h5>
            <h4>
                <small class="pull-left">R$</small>
                {{ float_to_money($contrato->valor_total_atual, '') }}
            </h4>
        </div>
        <div class="col-xs-3 text-right borda-direita">
            <h5>Valor Realizado</h5>
            <h4>
                <small class="pull-left">R$</small>
                0,00
            </h4>
        </div>
        <div class="col-xs-3 text-right borda-direita">
            <h5>Valor Saldo</h5>
            <h4>
                <small class="pull-left">R$</small>
                {{ float_to_money($contrato->valor_total_atual, '') }}
            </h4>
        </div>
    </div>

    <div class="panel panel-default panel-normal-table">
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-all-center">
                <thead>
                    <tr>
                        <th colspan="4"></th>
                        <th colspan="2">Contratado</th>
                        <th colspan="2">Realizado</th>
                        <th colspan="3">Saldo</th>
                        @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */ && !isset($impressao))
                            <th></th>
                        @endif
                    </tr>
                    <tr>
                        @if(!isset($impressao))
                        <th>Visualizar</th>
                        @endif
                        <th>Código</th>
                        <th {{ !isset($impressao)?'colspan="2"':'' }}>Descrição</th>
                        <th>Un. de medida</th>
                        <th>Qtd.</th>
                        <th>Valor Total</th>
                        <th>Qtd.</th>
                        <th>Valor Total</th>
                        <th>Qtd.</th>
                        <th>Valor Unitário</th>
                        <th>Valor Total</th>
                        @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */ && !isset($impressao))
                            <th style="width:18%">Ações</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        if(isset($itens->contrato_itens)) {
                            $itens = $itens->contrato_itens;
                        }
                    @endphp

                    @foreach($itens as $item)
                        <tr>
                            @if(!isset($impressao))
                            <td>
                                <button class="btn btn-primary btn-xs btn-flat"
                                    data-toggle="modal"
                                    data-target="#modal-historico-estruturado-{{ $item->id }}">
                                    <i data-toggle="tooltip"
                                        title="Apropriação"
                                        class="fa fa-fw fa-building"></i>
                                </button>
                                <button class="btn btn-flat btn-xs btn-default"
                                    data-toggle="modal"
                                    data-target="#modal-historico-{{ $item->id }}">
                                    <i data-toggle="tooltip"
                                        title="Histórico de Alteração"
                                        class="fa fa-fw fa-history"></i>
                                </button>
                            </td>
                            @endif
                            <td>{{ $item->insumo->codigo }}</td>
                            <td {{ !isset($impressao)?'colspan="2"':'' }}>{{ $item->insumo->nome }}</td>
                            <td>{{ $item->insumo->unidade_sigla }}</td>
                            <td>{{ float_to_money($item->qtd, '') }}</td>
                            <td>{{ float_to_money($item->valor_total) }}</td>
                            <td>{{ '0,00' }}</td>
                            <td>{{ 'R$ 0,00' }}</td>
                            <td>{{ float_to_money($item->qtd, '') }}</td>
                            <td>{{ float_to_money($item->valor_unitario) }}</td>
                            <td>{{ float_to_money($item->valor_total) }}</td>
                            @if($contrato->isStatus(2, 5) /* Aprovado ou Ativo */ && !isset($impressao))
                                <td>
                                    @include('contratos.itens_datatables_action', [
                                        'item' => $item
                                    ])
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td colspan="{{ !isset($impressao)?'12':'10' }}" style="{{ !isset($impressao)?'padding:0px':'' }}">
                        @include('contratos.modal-historico', ['item' => $item])
                        @include('contratos.modal-historico-estruturado', ['item' => $item])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="panel panel-default panel-normal-table">
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-all-center">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantidade</th>
                    <th>Unidade</th>
                    <th>Descrição do Material</th>
                    <th>OBSERVAÇÕES AO FORNECEDOR:</th>
                    <th>Preço Unitário</th>
                    <th>Preço Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    if(isset($itens->contrato_itens)) {
                        $itens = $itens->contrato_itens;
                    }
                @endphp

                @foreach($itens as $item)
                    @php
                        $obrigacoes_fornecedor = null;

                        if($item->qcItem) {
                            if($item->qcItem->quadroDeConcorrencia) {
                                $obrigacoes_fornecedor = $item->qcItem->quadroDeConcorrencia->obrigacoes_fornecedor;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ float_to_money($item->qtd, '') }}</td>
                        <td>{{ $item->insumo->unidade_sigla }}</td>
                        <td>{{ $item->insumo->nome }}</td>
                        <td>{{ $obrigacoes_fornecedor }}</td>
                        <td>{{ float_to_money($item->valor_unitario) }}</td>
                        <td>{{ float_to_money($item->valor_total) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
