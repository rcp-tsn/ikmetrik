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
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Eğitim Bilgileri</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                            <div class="form-group" style="text-align: -webkit-right;margin-right: 20px; padding-top: 20px">
                                <a href="{{ URL::previous() }}">
                                    <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                        <i class="la la-backspace"></i>Geri</div>
                                </a>
                            </div>

                        </div>

                        <!--end:: Card header-->
                        <div class="card-body">
                            <table class="table table-bordered" id="kt_datatable">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Adı Soyadı</th>
                                    <th>Departman</th>
                                    <th>Eğitim Düzey</th>
                                    <th>Bölüm</th>
                                    <th>İşlemler</th>

                                </tr>

                                </thead>
                                <tbody>
                                @foreach($employees as $key => $employee)
                                    <tr>
                                        <td>
                                            <div class="symbol symbol-circle symbol-lg-75">
                                                <img src="/{{$employee->avatar}}" alt="image">
                                            </div>
                                        </td>
                                        <td>{{$employee->full_name}}</td>
                                        <td>{{isset($employee->working_title) ? $employee->working_title->name : null}}</td>
                                        <td>{{config('variables.employees.completed_education')[$employee->completed_education($employee->id)]}}</td>
                                        <td>{{$employee->scholl()}}</td>
                                        <td><a href="#" data-id="{{$employee->id}}" data-name="{{$employee->first_name}}{{$employee->last_name}}"   data-toggle="modal" data-target="#schollModal" class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4 scholl">Okul Güncelle</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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


    <div class="modal fade" id="schollModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                {!! Form::open(['route' => ['ajax.discipline_store',$id], 'files'=>'true', 'class' => 'form', 'id' => 'discipline_form','readonly']) !!}
                <div class="modal-body">

                    <div class="form-group">
                        <label>Personel Adı Soyadı </label>
                        {!! Form::text('employee',null,['class'=>'form-control selectpicker', 'id'=>'employee','data-live-search'=>'true']) !!}
                    </div>

                    <div class="row">
                        <input type="hidden" name="" id="program_id" value="{{$id}}">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Lisans Türü</label>
                                {!! Form::select('completed_education',config('variables.employees.completed_education'),null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'completed_education']) !!}
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Bölüm Seçiniz</label>
                                <select class="form-control selectpicker" data-live-search="ture" name="scholl" id="scholl">

                                </select>
                                <span>Zorunlu Alan</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                    <button type="button" id="scholl_update" class="btn btn-primary font-weight-bold">Kaydet</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!--end::Entry-->
@stop

@section('js')
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
        $(".scholl").click(function ()
        {
            var employee_name =  $(this).data('name');
            $("#employee").val(employee_name);
            var employee_id = $(this).data('id');
            var page = $(".aktif").html();

            $("#scholl_update").click(function ()
            {

                var scholl =  $("#scholl").val();
                var completed_education =  $("#completed_education").val();
                $('#schollModal').modal('hide');
                $.ajax({
                    type: "POST",
                    url: '/ajax-employee-scholl-update',
                    data: {
                        _token: "{{csrf_token()}}",
                        employee_id:employee_id,
                        scholl:scholl,
                        completed_education:completed_education
                    },
                    success: function (data) {
                        Swal.fire({
                            position: "top-right",
                            icon: "success",
                            title: 'Güncelleme Başarılı Sayfa Yenilenince Veriler Değişecek',
                            showConfirmButton: false,
                            timer: 3500
                        })

                        var url = window.location.href;
                        var result = url.split('/');
                        var location = 'https://'+result[2] +'/'+ result[3] + '/' + result[4] + '/' + result[5] + '/' + result[6] + '/' + result[7] + '/' + page;
                        window.setTimeout(function(){
                            window.location.href = location;
                        }, 2000);

                    },

                    error: function (error) {
                        alert(error);
                    }
                });
            });
        });
    </script>

    <script>
        $("select").change(function() {

            var deger = $(this).val();
            $.ajax(
                {
                    type: 'get',
                    url:  '/employee-university/'+ deger,
                    data: "",
                    cache: false,
                    success: function(datas) {
                        $("#scholl option").remove();
                        $("#scholl").selectpicker("refresh");
                        $.each(datas['options'], function (i, item) {
                            $("#scholl").append(item);
                            $("#scholl").selectpicker("refresh");
                        });
                    }
                });

        });
    </script>

@endsection

