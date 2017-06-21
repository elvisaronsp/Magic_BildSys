<hr>
<div class="row" id="totalInsumos">
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Inicial</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ float_to_money($contrato->valor_total_inicial, '') }}
        </h4>
    </div>
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Atual</h5>
        <h4>
            <small class="pull-left">R$</small>
            {{ float_to_money($contrato->valor_total_atual, '') }}
        </h4>
    </div>
    <div class="col-md-3 text-right borda-direita">
        <h5>Valor Medido</h5>
        <h4>
            <small class="pull-left">R$</small>
            0,00
        </h4>
    </div>
    <div class="col-md-3 text-right borda-direita">
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
                    <th colspan="2">Saldo</th>
                    <th></th>
                </tr>
                <tr>
                    <th>#</th>
                    <th>Código do insumo</th>
                    <th>Descrição do insumo</th>
                    <th>Und de medida</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itens as $item)
                    <tr>
                        <td>
                            <button class="btn btn-primary btn-xs btn-flat"
                                data-toggle="modal"
                                data-target="#modal-historico-estruturado-{{ $item->id }}">
                                <i data-toggle="tooltip"
                                    title="Código Estruturado"
                                    class="fa fa-fw fa-building"></i>
                            </button>
                            <button class="btn btn-flat btn-xs btn-default"
                                data-toggle="modal"
                                data-target="#modal-historico-{{ $item->id }}">
                                <i data-toggle="tooltip"
                                    title="Histórico"
                                    class="fa fa-fw fa-history"></i>
                            </button>
                            @include('contratos.modal-historico', ['item' => $item])
                            @include('contratos.modal-historico-estruturado', ['item' => $item])
                        </td>
                        <td>{{ $item->insumo->codigo }}</td>
                        <td>{{ $item->insumo->nome }}</td>
                        <td>{{ $item->insumo->unidade_sigla }}</td>
                        <td>{{ float_to_money($item->qtd, '') }}</td>
                        <td>{{ float_to_money($item->valor_total) }}</td>
                        <td>{{ '0,00' }}</td>
                        <td>{{ 'R$ 0,00' }}</td>
                        <td>{{ float_to_money($item->qtd, '') }}</td>
                        <td>{{ float_to_money($item->valor_total) }}</td>
                        <td>
                            <button type="button"
                                class="btn btn-flat btn-xs btn-info"
                                title="Expandir"
                                onclick="showHideInfoExtra({{ $item->id }})">
                                Impostos
                                <i id="icone-expandir{{ $item->id }}"
                                    class="fa fa-caret-right fa-fw"></i>
                            </button>
                            @include('contratos.itens_datatables_action', [
                                'item' => $item
                            ])
                        </td>
                    </tr>
                    <tr id="dados-extras{{ $item->id }}" style="display: none">
                        <td colspan="11">
                            <table class="table table-bordered table-condensed table-no-margin">
                                <thead>
                                    <tr>
                                        <th>IRFF</th>
                                        <th>INSS</th>
                                        <th>PIS</th>
                                        <th>COFINS</th>
                                        <th>CSLL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ float_to_money($item->insumo->aliq_irff, '') }}%</td>
                                        <td>{{ float_to_money($item->insumo->aliq_inss, '') }}%</td>
                                        <td>{{ float_to_money($item->insumo->aliq_pis, '') }}%</td>
                                        <td>{{ float_to_money($item->insumo->aliq_cofins, '') }}%</td>
                                        <td>{{ float_to_money($item->insumo->aliq_csll, '') }}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

