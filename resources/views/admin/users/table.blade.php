{!! $dataTable->table(['width' => '100%','class'=>'table table-striped table-hover'], true) !!}

@section('scripts')
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        var oTable = null;

        $('#initial_date').bind('change', function () {
            putSession();
        });

        $('#final_date').bind('change', function () {
            putSession();
        });

        function putSession() {
            var initial_date = $('#initial_date').val();
            var final_date = $('#final_date').val();
            $.ajax({
                url: "/admin/putsession",
                data: {
                    initial_date: initial_date,
                    final_date: final_date
                }
            }).done(function (json) {
                if(json.success){
                    $("#dataTableBuilder").DataTable().draw();
                }
            });
        }

        function addFilters() {
            var cb_filter = $('.cb_filter');
            var cb_filter_label = $('.cb_filter_label');
            var filters = [];
            for( i=0; i < cb_filter.length; i++ ) {

                if(cb_filter[i].value.split(':')[1] == 'string'){
                    $('#block_fields').append('\
                        <div class="row form-group col-md-12">\
                            <div class="col-md-6">\
                                <label>'+cb_filter_label[i].innerHTML+'</label>\
                                <input type="text" name="filters['+cb_filter[i].value.split(':')[0]+']" class="form-control">\
                            </div>\
                            <div class="col-md-6" style="margin-top: 25px;">\
                                <select class="form-control">\
                                    <option value="between">Entre</option>\
                                    <option value="start">Começa com</option>\
                                    <option value="end">Termina com</option>\
                                </select>\
                            </div>\
                        </div>\
                    ');
                }

                if(cb_filter[i].value.split(':')[1] == 'date'){
                    $('#block_fields').append('\
                        <div class="row form-group col-md-12">\
                            <div class="col-md-6">\
                                <label>'+cb_filter_label[i].innerHTML+'</label>\
                                <input type="date" value="{{date("Y-m-d")}}" name="filters['+cb_filter[i].value.split(':')[0]+'_initial]" class="form-control">\
                            </div>\
                            <div class="col-md-6" style="margin-top: 25px;">\
                                <input type="date" value="{{date("Y-m-d")}}" name="filters['+cb_filter[i].value.split(':')[0]+'_final]" class="form-control">\
                            </div>\
                        </div>\
                    ');
                }

                if(cb_filter[i].value.split(':')[1] == 'boolean'){
                    $('#block_fields').append('\
                        <div class="form-group col-md-6" style="width: 48.8%;">\
                            <label>'+cb_filter_label[i].innerHTML+'</label>\
                            <select class="form-control">\
                                <option value="1">Sim</option>\
                                <option value="0">Não</option>\
                            </select>\
                        </div>\
                    ');
                }

                if(cb_filter[i].value.split(':')[1] == 'integer'){
                    var what = "'"+cb_filter[i].value.split(':')[0]+'_final'+"'";

                    $('#block_fields').append('\
                        <div class="row form-group col-md-12">\
                            <div class="col-md-3">\
                            <label>'+cb_filter_label[i].innerHTML+'</label>\
                                <input type="number" name="filters['+cb_filter[i].value.split(':')[0]+'_initial]" class="form-control">\
                            </div>\
                            <div class="col-md-3" style="margin-top: 25px;">\
                                <input type="number" name="filters['+cb_filter[i].value.split(':')[0]+'_final]" id="'+cb_filter[i].value.split(':')[0]+'_final" class="form-control">\
                            </div>\
                            <div class="col-md-6" style="margin-top: 25px;">\
                                <select class="form-control" onchange="filterFieldInteger(this.value, '+what+')">\
                                    <option value="between">Entre</option>\
                                    <option value="bigger">Maior que</option>\
                                    <option value="smaller">Menor que</option>\
                                    <option value="bigger_equal">Maior ou igual que</option>\
                                    <option value="smaller_equal">Menor ou igual que</option>\
                                </select>\
                            </div>\
                        </div>\
                    ');
                }

            }
        }

        function filterFieldInteger(value, what){
            if(value == 'between'){
                $('#'+what).css('display', '').val('');
            }else{
                $('#'+what).css('display', 'none').val('');
            }
        }

    </script>
@endsection