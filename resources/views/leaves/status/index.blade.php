@extends('layouts.app')
@section('content')
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
                                    <span class="d-block text-dark font-weight-bolder">İzin Talepleri </span>

                                </h3>


                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">


                                <div class="table-responsive">
                            <table class="table table-bordered" id="kt_datatable">

                                <thead>
                                <tr>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Personel Adı Soyadı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Durum</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Departman</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">İzin Bitiş</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">İzin Süre</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Detay</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">İşlemler</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">İzin Başlangıç</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($leaves as $leave)

                                    <tr>
                                        <td style="font-weight: bold;font-size: 15px">{{isset($leave->employee) ? $leave->employee->full_name :''}}</td>
                                        <td><?php  echo isset(config('variables.employees.leave_durum')[$leave->status]) ? config('variables.employees.leave_durum')[$leave->status] : '' ; ?></td>

                                        <td style="font-weight: bold;font-size: 15px">{{$leave->department()}}</td>

                                        <td style="font-weight: bold;font-size: 15px">{{ isset($leave->employee_leave) ? $leave->employee_leave->start_date->format('d/m/Y') : '' }} - {{isset($leave->employee_leave) ? $leave->employee_leave->start_time : ''}} </td>
                                        <td style="font-weight: bold;font-size: 15px">{{ isset($leave->employee_leave) ? $leave->employee_leave->job_start_date->format('d/m/Y') :'' }} - {{isset($leave->employee_leave) ? $leave->employee_leave->job_start_time :''}}</td>
                                        <td style="font-weight: bold;font-size: 15px">{{ isset($leave->employee_leave) ? \Illuminate\Support\Carbon::parse($leave->employee_leave->start_date->format('Y-m-d').' '.$leave->employee_leave->start_time)->diffForHumans(\Illuminate\Support\Carbon::parse($leave->employee_leave->job_start_date->format('Y-m-d').' '.$leave->employee_leave->job_start_time)) :'' }}
                                        <td><a href="{{route('leaves.show',createHashId($leave->employee_leave_id))}}" class="btn btn-success btn-sm mr-3">
                                                <i class="flaticon2-pie-chart"></i>Detay Göster</a></td>

                                        <td >

                                            @if(isset($leave->employee_leave))
                                                @if($leave->status == 1)
                                                    <a href="#" type="button"  data-leave="{{$leave->id}}" data-employeeLeave="{{isset( $leave->employee_leave) ?  $leave->employee_leave->id : 0}}" data-types="{{ $leave->type_authority  }}" data-status="1" data-url="{{env('APP_URL')}}"   class="btn btn-warning btn-sm mr-3 accept_button">
                                                        <i class="flaticon-file"></i>Onay Ver</a>
                                                    <a href="#" type="button" data-toggle="modal" data-target="#declineModal"   data-leave="{{$leave->id}}" data-employeeLeave="{{isset($leave->employee_leave) ? $leave->employee_leave->id : ''}}" data-types="{{ $leave->type_authority  }}" data-status="0"  data-url="{{env('APP_URL')}}"  class="btn btn-danger btn-sm mr-3 declineButton">
                                                        <i class="flaticon-file"></i>Reddet</a>
                                                @endif
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </div>
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

    <style>
        .modalaktivasyon {
            cursor: pointer;
        }

    </style>
    <div class="modal fade modalaktivasyon" id="LoadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">İzin Onay/Red İşlemi Yapılıyor</h5>
                </div>
                <div class="modal-body">
                    <p style="font-size: 15px;font-weight: bold" id="Pdks"></p>
                    <h3>Lütfen Bekleyiniz</h3>
                    <div class="loader-container">
                        <div class="loader">
                            <div class="square one"></div>
                            <div class="square two"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="declineModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">İzin Reddetme Nedeni</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                  <div class="row">
                      <label class="font-weight-bold" >İzin Reddetme Nedeni Giriniz</label>
                      <input type="text" class="form-control declineNotification" required name="notificaiton">
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                    <button type="button" class="btn btn-primary font-weight-bold declineModalButton">Kaydet</button>
                </div>
            </div>
        </div>
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
        $("body").on('click','.accept_button',function ()
        {
            var leave = $(this).data('leave');
            var employeeLeave = $(this).data('employeeleave');
            console.log(employeeLeave)
            var type = $(this).data('types');
            console.log(type)
            var status = $(this).data('status');
            var url = $(this).data('url');
            var notification = null;
            if (type == 3)
            {

                $("#LoadingModal").modal('show');
                window.setTimeout(function(){

                }, 3000);

                window.setTimeout(function(){
                    $("#Pdks").html("ONAYLAMA İŞLEMİ GERÇEKLEŞ OLUP, VERİ PDKS SİSTEMİNE KAYDEDİLMİŞTİR.");
                    window.location.href= url + "/leaves/ajax/accept/" + leave + "/" + employeeLeave + "/"+ type + "/" + status + "/" + notification;

                }, 6000);


            }
            else
            {
                $("#Pdks").html("");

                $("#LoadingModal").modal('show');
                window.setTimeout(function(){
                    window.location.href= url + "/leaves/ajax/accept/" + leave + "/" + employeeLeave + "/"+ type + "/" + status  + "/" + notification;

                }, 5000);
            }



        });
    </script>

    <script>

        $("body").on('click','.declineButton',function ()
        {


            var leave = $(this).data('leave');
            var employeeLeave = $(this).data('employeeleave');
            console.log(employeeLeave)
            var type = $(this).data('types');
            console.log(type)
            var status = $(this).data('status');
            var url = $(this).data('url');

            $("body").on('click','.declineModalButton',function ()
            {
                var notification = $(".declineNotification").val();
                $("#declineModal").modal('hide');
                $("#LoadingModal").modal('show');
                window.setTimeout(function(){
                    window.location.href= url + "/leaves/ajax/accept/" + leave + "/" + employeeLeave + "/"+ type + "/" + status + "/" + notification;

                }, 4000);
            });



        });

    </script>
@endpush

