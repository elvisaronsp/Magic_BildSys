<!-- Nome Field -->
<div class="form-group col-md-6">
    {!! Form::label('nome', 'Nome:') !!}
    <p class="form-control">{!! $mascaraPadrao->nome !!}</p>
</div>

<!-- Tipo de Orçamento Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('orcamento_tipo_id', 'Tipo de Orçamentos:') !!}
    <p class="form-control">{!! $mascaraPadrao->tipoOrcamentos->nome !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Criado em:') !!}
    <p class="form-control">{!! $mascaraPadrao->created_at->format('d/m/Y')  !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Atualizado em:') !!}
    <p class="form-control">{!! $mascaraPadrao->updated_at->format('d/m/Y')  !!}</p>
</div>