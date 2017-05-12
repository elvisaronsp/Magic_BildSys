<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control">{!! $compradorInsumo->id !!}</p>
</div>

<!-- User Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('user_id', 'User Id:') !!}
    <p class="form-control">{!! $compradorInsumo->user_id !!}</p>
</div>

<!-- Insumo Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('insumo_id', 'Insumo Id:') !!}
    <p class="form-control">{!! $compradorInsumo->insumo_id !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Created At:') !!}
    <p class="form-control">{!! $compradorInsumo->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p class="form-control">{!! $compradorInsumo->updated_at !!}</p>
</div>

