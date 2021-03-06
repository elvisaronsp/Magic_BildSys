<!-- nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('tarefa', 'Tarefa:') !!}
    {!! Form::text('tarefa', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- resumo Field -->
<div class="form-group col-sm-2">
    {!! Form::label('resumo', 'Resumo:') !!}
    {!! Form::select('resumo' , array('0' => 'Não', '1' => 'Sim'),  null, ['class' => 'form-control']) !!}
</div>

<!-- critica Field -->
<div class="form-group col-sm-2">
    {!! Form::label('critica', 'Crítica:') !!}
	{!! Form::select('critica' , array('0' => 'Não', '1' => 'Sim'),  null, ['class' => 'form-control']) !!}    
</div>

<!-- torre Field -->
<div class="form-group col-sm-2">
    {!! Form::label('torre', 'Torre:') !!}
    {!! Form::text('torre', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- pavimento Field -->
<div class="form-group col-sm-3">
    {!! Form::label('pavimento', 'Pavimento:') !!}
    {!! Form::text('pavimento', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.tarefa_padrao.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>