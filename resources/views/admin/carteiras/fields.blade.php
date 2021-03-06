<!-- nome Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('carteiraUsers', 'Comprador para esta carteira:') !!}
    {!! Form::select('carteiraUsers[]', $usuarios , (!isset($carteira )? null : $carteiraUsers), ['class' => 'form-control', 'id'=>"carteiraUsers", 'multiple'=>"multiple"]) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('carteiraTipoEqualizacaoTecnicas', 'Tipos Equalização Técnica nesta carteira:') !!}
    {!! Form::select('carteiraTipoEqualizacaoTecnicas[]', $equalizacoesTecnicas , (!isset($carteira )? null : $carteiraTipoEqualizacaoTecnicas), ['class' => 'form-control', 'id'=>"carteiraTipoEqualizacaoTecnicas", 'multiple'=>"multiple"]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right flat', 'type'=>'submit']) !!}
    <a href="{!! route('admin.carteiras.index') !!}" class="btn btn-danger flat"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        function formatResult (obj) {
            if (obj.loading) return obj.text;

            var markup =    "<div class='select2-result-obj clearfix'>" +
                "   <div class='select2-result-obj__meta'>" +
                "       <div class='select2-result-obj__title'>" + obj.name + "</div>"+
                "   </div>"+
                "</div>";

            return markup;
        }

        function formatResultSelection (obj) {
            if(obj.name){
                return obj.name;
            }
            return obj.text;
        }

        $(function(){

            $('#carteiraUsers, #carteiraTipoEqualizacaoTecnicas').select2({
                language: "pt-BR",
                theme:'bootstrap',
            });


            /*$('#carteiraUsers').select2({
                language: "pt-BR",
                theme:'bootstrap',
                ajax: {
                    url: "/admin/users/busca",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page
            });
			
			$('#carteiraTipoEqualizacaoTecnicas').select2({
                language: "pt-BR",
                theme:'bootstrap',
                ajax: {
                    url: "/buscar/tipo-equalizacao-tecnicas",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page
            });*/
           
		});
    </script>
@stop
