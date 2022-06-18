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
                @if(Auth::user()->hasRole('e-bordro'))
                <a href="{{ route('notifications.create') }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> YENİ TEBLİGAT EKLE
                </a>
                    @endif
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
                                    <span class="d-block text-dark font-weight-bolder">Tebligatlar</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ad Soyad</th>
                                    <th>Dosya Adı</th>
                                    <th>Tarih</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $sira = 1; @endphp
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td>{{$sira ++}}</td>
                                        <td>{{!empty($notification->employee()) ? $notification->employee() : null}}</td>
                                        <td>{{!empty($notification->name) ? $notification->name : null}}</td>
                                        <td>{{!empty($notification->date) ? $notification->date->format('d/m/Y') : null}}</td>
                                        <td>
                                            @if($notification->sms_status == 1)
                                                @if($notification->zamane_accept == 0)
                                                    <span class="label label-lg label-light-danger label-inline font-weight-bold py-4">Reddedildi</span>
                                                @elseif($notification->zamane_accept == 1)
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Onay bekliyor</span>
                                                @else
                                                    <span class="label label-lg label-light-success label-inline font-weight-bold py-4">Kabul Edildi</span>
                                                @endif
                                            @else
                                                <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Gönderim Bekliyor </span>
                                                <br>


                                                <a style="margin-top: 10px" title="TÜM PERSONELLERE SMS GÖNDER"  data-id="{{$notification->id}}" class="btn btn-outline-success btn-sm mr-3 sms">
                                                    <i class="flaticon2-chat-1"></i>Gönder</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                                <a href="{{isset($notification->file) ? $notification->file:null}}" download="" class=""><button class="btn btn-success">Evrak Göster</button></a>
                                                @if($notification->sms_status == 0)
                                                    <div class="row" align="left">
                                                        <button data-id="{{$notification->id}}" aria-label="" class="btn btn-danger btn-icon-left m-b-10 companyDelete" type="button"><i class="pg-icon">trash</i>Sil</button>
                                                    </div>
                                                @else
                                                        <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Gönderilen Evraklar Silinemez</span>
                                                @endif
                                            @else

                                                <a href="{{isset($notification->file) ? $notification->file:null}}" download="" class=""><button class="btn btn-success">Evrak Göster</button></a>
                                                @if($notification->zamane_accept == 1)
                                                    <button class="btn btn-warning accept_button m-l-3" data-file="{{$notification->id}}" data-id="{{createHashId($notification->employee_id())}}" type="button" >Onay Ver</button>
                                                    {{--                                                    <button class="btn btn-danger protestButton m-l-3" data-target="#protest" data-toggle="modal"   data-bordro="{{$notification->id}}" data-id="{{\Vinkla\Hashids\Facades\Hashids::encode($notification->employee_id())}}">İtiraz Et</button>--}}
                                                @endif

                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>
            @include('payrolls.insertModal')
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
        $('.protestButton').click(function () {
            var working_id = $(this).data('id');
            var payrol_id = $(this).data('bordro');
            $(".payroll_id3").val(payrol_id);
            $(".working_id3").val(working_id);

            $('.protest_submit').click(function ()
            {
                $("#protestForm").submit();
            });


        });
    </script>
    <script>

        $('.sms').click(function () {
            var id = $(this).data('id');
            console.log(id);
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem Geri alınamaz Evrak  Kontrol Edildikten Sonra Yapınız!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = '{{env('APP_URL')}}/employee/notification/sms/'+id;
                    } else {
                        swal("Silme İşlemi İptal Edildi");
                    }
                });
        });

    </script>

    <script>

        $('.accept_button').click(function () {
            var working_id = $(this).data('id');
            var notification_id = $(this).data('file');
            $(".payroll_i2").val(notification_id);
            $(".working_i2").val(working_id);


            swal({
                title: "Eminmisiniz?",
                text: " Onaylayın!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '/send_sms',
                            type: 'POST',
                            data: {
                                'working_id':working_id,

                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function () {
                                $('#NotificationSms').modal('show');
                                $('.code').click(function () {
                                    $("#notificaiton_accept_employee").submit();
                                });
                            },
                            error: function () {
                            }
                        });
                    } else
                    {
                        swal("Onay İşlemi İptal Edildi");
                    }
                });






        });






    </script>
@endpush


