@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Disiplin Suçları</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>

                        <div class="form-group" style="text-align: -webkit-right;margin-right: 20px;">
                            <div data-repeater-create="" data-toggle="modal" data-target="#disciplineModal" class="btn font-weight-bold btn-warning">
                                <i class="la la-plus"></i>Disiplin Suçu Ekle</div>
                        </div>



                        <!--end:: Card header-->
                        <div class="card-body">
                            {!! Form::open(['route' => ['ajax.discipline_store',$id], 'files'=>'true', 'class' => 'form', 'id' => 'discipline_form']) !!}
                            <div class="modal-body">

                                <div class="form-group">
                                    <label>Personel Seçiniz</label>
                                    {!! Form::select('employee',$subordinates,null,['class'=>'form-control selectpicker', 'id'=>'employee','data-live-search'=>'true']) !!}
                                </div>

                                <div class="">
                                    <label>Disiplin Suçu</label>
                                    <table class="table table-responsive" id="kt_datatable2">
                                        <thead>
                                        <th>#</th>
                                        <th>Suç</th>
                                        <th>Türü</th>
                                        </thead>
                                        <tbody>
                                        @foreach($company_disciplines as $discip)
                                            <tr>
                                                <td>
                                                    <input type="radio" name="discipline[{{$discip->id}}]">
                                                </td>
                                                <td>{{$discip->name}}</td>
                                                <td>{{$discip->type}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>


                                <input type="hidden" name="" id="program_id" value="{{$id}}">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tarih</label>
                                        {!! Form::date('date',null,['class'=>'form-control','id'=>'discipline_date']) !!}
                                    </div>
                                </div>


                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                                <button type="button" id="discipline_store" class="btn btn-primary font-weight-bold">Kaydet</button>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
                <!--begin::Card body-->

                <!--end:: Card body-->
            </div>
            <!--end:: Card-->


            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>


    <!--end::Entry-->
@stop

@push('scripts')
    <script>
        "use strict";
        var KTDatatablesAdvancedColumnRendering = function() {

            var init = function() {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: true
                });

                $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
            };

            return {

                //main function to initiate the module
                init: function() {
                    init();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesAdvancedColumnRendering.init();
        });
    </script>

    <script>
        $(document).ready(function () {
            $("#discipline_store").click(function ()
            {
                var id = $("#program_id").val();
                console.log(id);
                var  employee =  $('select[name*=employee] option').filter(':selected').val()
                console.log(employee);
                var notification = $("#notification").val();
                console.log(employee);
                var discipline_date = $("#discipline_date").val();
                console.log(discipline_date);
                $('#disciplineModal').modal('hide'); // Modal Hidden
                var form = $(this);
                $.ajax({
                    type: "POST",
                    url: '/ajax-discipline-store/'+id,
                    data: {
                        _token: "{{csrf_token()}}",
                        employee:employee,
                        notification:notification,
                        date:discipline_date
                    },
                    success: function (data) {
                        $( "#kt_datatable" ).append(data);
                    },

                    error: function (error) {
                        alert(error);
                    }
                });
            });

        });
    </script>

    <script>
        "use strict";
        var KTDatatablesAdvancedColumnRendering = function() {

            var init = function() {
                var table = $('#kt_datatable2');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: true
                });

                $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
            };

            return {

                //main function to initiate the module
                init: function() {
                    init();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesAdvancedColumnRendering.init();
        });
    </script>

@endpush



