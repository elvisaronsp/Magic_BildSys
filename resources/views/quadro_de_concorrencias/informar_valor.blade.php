@extends('layouts.front')

@section('content')
    <section class="content-header">
      <h1>Informar valores fornecedor</h1>
    </section>

    {!!
      Form::open([
        'route' => ['quadro-de-concorrencias.informar-valor', $quadro->id],
        'id' => 'informar-valores-form',
        'class' => 'content'
      ])
    !!}

    @if ($errors->count())
      <div class="alert alert-danger">
        <p>Por favor, corrija os seguintes problemas para enviar o formulário</p>
        <ul>
          @foreach ($errors->unique() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="box box-solid">
      <div class="box-body">
        <div class="row">
          <div class="col-md-4">
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label for="fornecedor_id">Fornecedor</label>
              {!!
                Form::select(
                  'fornecedor_id',
                  $fornecedores,
                  old('fornecedor_id'),
                  [ 'class' => 'select2 form-control' ]
                )
              !!}
            </div>
            <a href="#modal-fornecedor"
              data-toggle="modal"
              class="btn btn-primary btn-block">
              Obrigações do Fornecedor
            </a>
            <a href="#modal-bild"
              data-toggle="modal"
              class="btn btn-primary btn-block">
              Obrigações BILD
            </a>
          </div>
          <div class="col-md-10">
            <div class="box box-muted box-equalizacao-tecnica">
              <div class="box-header with-border">Equalização Técnica</div>
              <div class="box-body">
                <table class="table table-responsive table-striped table-align-middle">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Item</th>
                      <th>Sim/Não/Ciência</th>
                      <th>Obs</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($equalizacoes as $key =>  $equalizacao)
                      <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $equalizacao->nome }}</td>
                        <td>
                          {!! Form::hidden("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checkable_type]", $equalizacao->getTable()) !!}
                          {!! Form::hidden("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checkable_id]", $equalizacao->id) !!}
                          @if($equalizacao->obrigatorio)
                            <div class="checkbox">
                              <label>
                                {!!
                                Form::checkbox("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checked]", '1') !!}
                                Obrigatório
                              </label>
                            </div>
                          @else
                            <label class="radio-inline">
                              {!!
                                Form::radio(
                                  "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checked]",
                                  '1'
                                )
                              !!}
                              Sim
                            </label>
                            <label class="radio-inline">
                              {!!
                                Form::radio(
                                  "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checked]",
                                  '0'
                                )
                              !!}
                              Não
                            </label>
                          @endif
                        </td>
                        <td>
                          @if(!$equalizacao->obrigatorio)
                            {!!
                              Form::textarea(
                                "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][obs]",
                                old("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][obs]"),
                                [
                                  'placeholder' => 'Observação',
                                  'class' => 'form-control',
                                  'rows' => 3,
                                  'cols' => 25
                                ]
                              )
                            !!}
                          @else
                            <span class="text-muted">#</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="box-footer">
                <a href="#modal-anexos" data-toggle="modal" class="btn btn-primary">
                  Anexos
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-3 hidden">
            <div class="form-group">
              <div class="row">
                <label class="col-md-5">
                  % Mão de Obra
                </label>
                <div class="col-md-7">
                  <input type="text" class="form-control decimal">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-md-5">
                  % Material
                </label>
                <div class="col-md-7">
                  <input type="text" class="form-control decimal">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-md-5">
                  % Faturamento Direto
                </label>
                <div class="col-md-7">
                  <input type="text" class="form-control decimal">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="box box-solid">
      <div class="box-body">
        <table class="table table-responsive table-striped table-align-middle">
          <thead>
            <tr>
              <th>Cod. Insumo</th>
              <th>Un</th>
              <th>Obs. Fornecedor</th>
              <th>Quantidade QC</th>
              <th>Tabela Tems</th>
              <th>Valor Unitário</th>
              <th>Valor Total</th>
              <th>Obra - Cidade</th>
            </tr>
          </thead>
          <tbody>
            @foreach($quadro->itens as $item)
              <tr class="js-calc-row">
                <td>{{ $item->insumo->codigo }}</td>
                <td>{{ $item->insumo->unidade_sigla }}</td>
                <td>
                  {!!
                    Form::textarea(
                      "itens[{$item->id}][obs]",
                      $item->obs,
                      [
                        'placeholder' => 'Observação',
                        'class' => 'form-control',
                        'rows' => 3,
                        'cols' => 25,
                        'disabled' => 'disabled',
                      ]
                    )
                  !!}
                </td>
                <td class="js-calc-amount">
                  {{ $item->qtd }}
                  {!! Form::hidden("itens[{$item->id}][qtd]", $item->qtd) !!}
                </td>
                <td>
                  {!!
                    Form::textarea(
                      "itens[{$item->id}][tems]",
                      $item->tems,
                      [
                        'placeholder' => 'Tems',
                        'class' => 'form-control',
                        'rows' => 3,
                        'cols' => 25,
                        'disabled' => 'disabled',
                      ]
                    )
                  !!}
                </td>
                <td>
                  {!!
                    Form::text(
                      "itens[{$item->id}][valor_unitario]",
                      old("itens[{$item->id}][valor_unitario]"),
                      [
                        'class' => 'form-control js-calc-price money',
                      ]
                    )
                  !!}
                </td>
                <td class="js-calc-result">
                  R$ 0,00
                </td>
                <td>
                  @foreach($item->ordemDeCompraItens->pluck('obra')->flatten()->unique() as $key => $obra)
                    {{ $obra->nome }} - {{ $obra->cidade->nome }}{{ !$loop->last ? ',' : '' }}
                  @endforeach
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="box-footer text-right">
        <input type="submit"
        class="btn btn-danger"
        value="Rejeitar"
        id="reject">
        <input type="submit"
        class="btn btn-success"
        value="Salvar"
        id="save">
      </div>
    </div>
    {!! Form::close() !!}
    <div class="modal fade" id="modal-anexos" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title"> Anexos </h4>
          </div>
          <div class="modal-body">
            <ul>
              @foreach($anexos as $anexo)
                <li>
                  <a target="_blank" href="{{ $anexo->url }}">
                    {{ $anexo->nome }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-fornecedor" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"> Obrigações do Fornecedor </h4>
          </div>
          <div class="modal-body">
            {{ $quadro->obrigacoes_fornecedor }}
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-bild" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"> Obrigações Bild </h4>
          </div>
          <div class="modal-body">
            {{ $quadro->obrigacoes_bild }}
          </div>
        </div>
      </div>
    </div>
    {!!
      Form::select(
        'desistencia_motivo_id',
        $motivos,
        null,
        [
          'class' => 'hidden form-control input-lg',
          'id' => 'desistencia_motivo_id',
          'required' => 'required'
        ]
      )
    !!}
@endsection

