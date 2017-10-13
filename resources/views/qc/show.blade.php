@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            @if($emAprovacao)
                Aprovação de
            @endif
            Q.C. Alvulso #{{ $qc->id }}
            @include('qc.aprovacao')
        </h1>
    </section>
    <div class="content">
        {!! Form::model($qc, ['route' => ['qc.update', $qc->id], 'files' => true, 'method' => 'patch']) !!}
        <div class="box">
            <div class="box-body">
                <div class="row">
                    @include('qc.show_fields')
                </div>
            </div>
            <div class="box-footer">
                <a href="{!! route('qc.index') !!}"
                    class="btn btn-default">
                   <i class="fa fa-arrow-left"></i>
                   {{ ucfirst(trans('common.back')) }}
                </a>
                <div class="pull-right">
                    @if($qc->canClose())
                        <button class="btn btn-info"
                            type="button"
                            id="fechar-qc"
                            data-id="{{ $qc->id }}">
                            <i class="fa fa-check-square"></i> Fechar Q.C.
                        </button>
                    @endif
                    @if(isset($workflowAprovacao) && $qc->isEditable($workflowAprovacao))
                        <button type="submit" class="btn btn-success btn-flat">
                            <i class="fa fa-floppy-o"></i> Salvar
                        </button>
                    @endif
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <div class="hidden">
        {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        options_motivos = document.getElementById('motivo').innerHTML;
    </script>
    <script src="{{ asset('js/qc-edit-actions.js') }}"></script>
@append
