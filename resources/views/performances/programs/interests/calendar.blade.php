@extends('layouts.app')
@section('content')
    <div class="subheader py-2 py-lg-4  subheader-transparent" id="kt_subheader">

        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

            <!--begin::Info-->

            <div class="d-flex align-items-center flex-wrap mr-1">

                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline mr-5">

                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-2 mr-5">
                    </h5>

                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                            </a>
                        </li>
                    </ul>

                    <!--end::Breadcrumb-->
                </div>

                <!--end::Page Heading-->
            </div>

            <!--end::Info-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
            </div>
        </div>
    </div>
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
                                    <span class="d-block text-dark font-weight-bolder">Personel Ücret Değişikliği</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <div class="row" align="center">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        {!! Form::select('employees',$employees,null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'employee_id']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group" >
                                       <button type="button" class="btn btn-success filtrele">Filtrele</button>
                                    </div>
                                </div>

                            </div>

                            <table class="table table-bordered" id="kt_datatable">

                                <thead>
                                <tr>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Adı Soyadı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Tarih</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Zam Türü</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Yüzde</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Eski Ücret</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Yeni Ücret</th>
                                </tr>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>

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
        $(".filtrele").click(function ()
        {
            var employee_id = $("#employee_id").val();
            $.ajax({
                    type:'GET',
                    url:'/employee-interest/salary/'+employee_id,
                    success:function (datas)
                    {
                        if (datas['type']=='success')
                        {
                            $("#kt_datatable tbody tr").append().remove();
                            $.each( datas['data'], function( key, value ) {
                                $("#kt_datatable tbody").append(value);
                            });



                        }
                    },



                });
        })
    </script>
@endpush

