@inject('carbon', 'Carbon\Carbon')

<div class='btn-group'>
  @if($item->qcItem)
    <a href="javascript:void(0)"
      title="Reapropriações"
      data-toggle="popover"
      data-container="body"
      data-external-content="#reapropriacao-{{ $item->id }}"
      class='btn btn-info btn-xs btn-flat'>
      <i class="fa fa-asterisk fa-fw"></i>
    </a>
  @endif
  <a href="javascript:void(0)"
    title="{{ $item->servico }} / {{ $item->insumo->nome }}"
    data-toggle="popover"
    data-container="body"
    data-external-content="#history-table-{{ $item->id }}"
    class='btn btn-default btn-xs btn-flat'>
    <i class="fa fa-history fa-fw"></i>
  </a>
</div>

@if($item->qcItem)
  <div id="reapropriacao-{{ $item->id }}" class="hidden">
    @if($reapropriacoes_dos_itens->isEmpty() && $reapropriacoes_de_reapropriacoes->isEmpty())
      <p>Não foram realizadas reapropriações nestes itens.</p>
    @endif

    @foreach($reapropriacoes_dos_itens as $id)
      @php $ordemDeCompraItem = $item->qcItem->ordemDeCompraItens->where('id', $id)->first(); @endphp
      <div class="box box-muted">
        <div class="box-header with-border">
          {{ $ordemDeCompraItem->codigoServico() }}
          <span class="label label-info label-normalize">
            Total: {{ $ordemDeCompraItem->qtd_formatted }}
          </span>
          <span class="label label-warning label-normalize">
            Sobrou: {{ $ordemDeCompraItem->qtd_sobra_formatted }}
          </span>
        </div>
        <div class="box-body">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>Código</th>
                <th>Quantidade</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ordemDeCompraItem->reapropriacoes as $re)
                <tr>
                  <td>{{ $re->codigoServico() }}</td>
                  <td>
                    {{ float_to_money($re->qtd, '') }} {{ $re->insumo->unidade_sigla }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach
    @foreach($reapropriacoes_de_reapropriacoes as $re)
      <div class="box box-muted">
        <div class="box-header with-border">
          {{ $re->codigoServico() }}
          <span class="label label-info label-normalize">
            Total: {{ $re->qtd_formatted }}
          </span>
          <span class="label label-warning label-normalize">
            Sobrou: {{ $re->qtd_sobra_formatted }}
          </span>
        </div>
        <div class="box-body">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>Código</th>
                <th>Quantidade</th>
              </tr>
            </thead>
            <tbody>
              @foreach($re->reapropriacoes as $re)
                <tr>
                  <td>{{ $re->codigoServico() }}</td>
                  <td>
                    {{ float_to_money($re->qtd, '') }} {{ $re->insumo->unidade_sigla }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach
  </div>
@endif

<div id="history-table-{{ $item->id }}" class="hidden">
  <table class="table table-striped table-condensed">
    <thead>
      <tr>
        <th></th>
        <th colspan="2" class="text-center">Antes</th>
        <th colspan="2" class="text-center">Depois</th>
        <th></th>
      </tr>
      <tr>
        <th>Movimentação</th>
        <th>Quantidade</th>
        <th>Valor</th>
        <th>Quantidade</th>
        <th>Valor</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
      @foreach($item->modificacoes->toArray() as $modificacao)
        <tr>
          <td>{{ $modificacao['tipo_modificacao'] }}</td>
          <td>{{ float_to_money($modificacao['qtd_anterior'], '') }}</td>
          <td>{{ float_to_money($modificacao['valor_unitario_anterior'], '') }}</td>
          <td>{{ float_to_money($modificacao['qtd_atual'], '') }}</td>
          <td>{{ float_to_money($modificacao['valor_unitario_atual'], '') }}</td>
          <td>{{ $carbon->parse($modificacao['created_at'])->format('d/m/Y') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
