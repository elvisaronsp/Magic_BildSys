@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
                <span class="pull-left title">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Ordens de compra
                </span>
                </div>

                <div class="col-md-3">
                    <button type="button" class="btn btn-success button-large-green" data-dismiss="modal">
                        Comprar insumos
                    </button>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box-body">

        @include('layouts.filters')

            <table class="table">
                <thead class="head-table">
                <tr>
                    <th class="row-table">#</th>
                    <th class="row-table">First Name</th>
                    <th class="row-table">Status</th>
                    <th class="row-table">Detalhe</th>
                    <th class="row-table">Aprovar</th>
                    <th class="row-table">Reprovar</th>
                    <th class="row-table">Troca</th>
                    <th class="row-table">Incluir insumo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row" class="row-table">1</th>
                    <td class="row-table">Mark</td>
                    <td class="row-table"><i class="fa fa-circle green"></i></td>
                    <td class="row-table"><i class="fa fa-eye"></i></td>
                    <td class="row-table"><i class="glyphicon glyphicon-ok grey"></i></td>
                    <td class="row-table"><i class="fa fa-times grey"></i></td>
                    <td class="row-table"><i class="fa fa-exchange grey"></i></td>
                    <td class="row-table"><i class="fa fa-plus"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="row-table">2</th>
                    <td class="row-table">Jacob</td>
                    <td class="row-table"><i class="fa fa-circle orange"></i></td>
                    <td class="row-table"><i class="fa fa-eye"></i></td>
                    <td class="row-table"><i class="glyphicon glyphicon-ok green"></i></td>
                    <td class="row-table"><i class="fa fa-times red"></i></td>
                    <td class="row-table"><i class="fa fa-exchange blue"></i></td>
                    <td class="row-table"><i class="glyphicon glyphicon-ok green"></i></td>
                </tr>
                <tr>
                    <th scope="row" class="row-table">3</th>
                    <td class="row-table">Larry</td>
                    <td class="row-table"><i class="fa fa-circle red"></i></td>
                    <td class="row-table"><i class="fa fa-eye"></i></td>
                    <td class="row-table"><i class="glyphicon glyphicon-ok grey"></i></td>
                    <td class="row-table"><i class="fa fa-times grey"></i></td>
                    <td class="row-table"><i class="fa fa-times red"></i></td>
                    <td class="row-table"><i class="fa fa-plus"></i></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection