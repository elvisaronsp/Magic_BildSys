@extends(request('is_modal') ? 'layouts.modal' : 'layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Solicitação de insumo
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => ['solicitar_insumo.store']]) !!}

                    @include('admin.solicitacao_insumos.fields')
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            <button class="btn btn-success pull-right">
                                <i class="fa fa-save"></i> Salvar
                            </button>
                            <a href="{{ route('solicitar_insumo.store') }}"
                                class="btn btn-default"
                                id="cancel">
                                <i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}
                            </a>
                        </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
