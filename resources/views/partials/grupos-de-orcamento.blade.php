<div class="row">
    @if(isset($insumo))
        <input type="hidden"
            id="grupos_de_orcamento_insumo_id"
            value="{{ $insumo }}">
    @endif


        <!-- Grupos de insumo Field -->
        {!! Form::hidden('grupo_id',\App\Models\Grupo::where('codigo', '01')->whereNull('grupo_id')->first()->id,['id'=>'grupo_id', 'class'=>'js-grupos-orc']) !!}
        <!-- SubGrupos1 de insumo Field -->
        <div class="form-group col-sm-12 col-md-3">
            {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
            {!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter js-grupos-orc', 'id'=>'subgrupo1_id', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');']) !!}
        </div>

        <!-- SubGrupos2 de insumo Field -->
        <div class="form-group col-sm-12 col-md-3">
            {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
            {!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter js-grupos-orc', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');']) !!}
        </div>

        <!-- SubGrupos3 de insumo Field -->
        <div class="form-group col-sm-12 col-md-3">
            {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
            {!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter js-grupos-orc', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');']) !!}
        </div>

        <!-- SubGrupos4 de insumo Field -->
        <div class="form-group col-sm-12 col-md-3">
            {!! Form::label('servico_id', 'Serviço:') !!}
            {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control select2 js-filter js-grupos-orc', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\');']) !!}
        </div>

</div>

@section('scripts')
  <script src="{{ asset('js/grupos-de-orcamento.js') }}"></script>
@append
