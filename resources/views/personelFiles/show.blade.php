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
                    <a href="{{ route('personelFiles.create') }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
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
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Dosyası</span>
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
                                    <th>#</th>
                                    <th>Ad Soyad</th>
                                    <th>Dosya Türü</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                    <th>Dosya Açıklama</th>
                                    <th>Gönderilme Tarihi</th>
                                    <th>Kabul Edilme Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $sira = 1; @endphp
                                @foreach($personelFiles as $personel)
                                    <tr>
                                        <td>{{$sira ++}}</td>
                                        <td>{{!empty($personel->employee->full_name) ? $personel->employee->full_name : null}}</td>
                                        <td>{{!empty($personel->file_type) ? $personel->file_type->name : null}}</td>
                                        <td>
                                            @if($personel->sms_status == 1)
                                                @if($personel->zamane_accept == 0)
                                                    <span class="label label-lg label-light-danger label-inline font-weight-bold py-4">Reddedildi</span>
                                                @elseif($personel->zamane_accept == 1)
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Onay bekliyor</span>
                                                @else
                                                    <span class="label label-lg label-light-success label-inline font-weight-bold py-4">Kabul Edildi</span>
                                                @endif
                                            @else
                                                <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Gönderim Bekliyor </span>
                                                <br>


                                                <a style="margin-top: 10px" title="TÜM PERSONELLERE SMS GÖNDER"  data-id="{{$personel->id}}" class="btn btn-outline-success btn-sm mr-3 sms">
                                                    <i class="flaticon2-chat-1"></i>Gönder</a>
                                            @endif
                                        </td>
                                        <td>{{!empty($personel->date) ? $personel->date->format('d/m/Y') : null}}</td>
                                        <td>{{!empty($personel->notification) ? $personel->notification : null}}</td>
                                        <td>{{!empty($personel->upload_date) ? $personel->upload_date->format('d/m/Y  H:i:s') : null}}</td>
                                        <td>{{!empty($personel->accept_date) ? $personel->accept_date->format('d/m/Y  H:i:s' ) : null}}</td>


                                        <td>

                                            <div class="btn-group">
                                                <a href="{{isset($personel->file) ? $personel->file:null}}" download="" title="EVRAKLARI GÖSTER" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
                                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                               <polygon points="0 0 24 0 24 24 0 24"/>
                                                             <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                             <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                                             <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                                            </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                </a>
                                            @if( Auth::user()->hasAnyRole('e-bordro','kvkk') and $personel->zamane_accept == 0)

                                                <a href="{{route('personelFiles.edit',createHashId($personel->id))}}"  title="Düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area editModal">
                                                <span class="svg-icon svg-icon-md svg-icon-primary">
																	<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Communication/Write.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"></path>
																			<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
																		</g>
																	</svg>
                                                    <!--end::Svg Icon-->
																</span>
                                                </a>
                                                @elseif(Auth::user()->hasAnyRole('e-bordro','kvkk') and $personel->zamane_accept == 1)
                                                <a href="#" data-toggle="modal" data-target="#fileUpload" data-id="{{$personel->id}}"  title="Onaylı Dosya Yükle" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area fileUploadId">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\Uploaded-file.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                         <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                             <polygon points="0 0 24 0 24 24 0 24"/>
                                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                <path d="M8.95128003,13.8153448 L10.9077535,13.8153448 L10.9077535,15.8230161 C10.9077535,16.0991584 11.1316112,16.3230161 11.4077535,16.3230161 L12.4310522,16.3230161 C12.7071946,16.3230161 12.9310522,16.0991584 12.9310522,15.8230161 L12.9310522,13.8153448 L14.8875257,13.8153448 C15.1636681,13.8153448 15.3875257,13.5914871 15.3875257,13.3153448 C15.3875257,13.1970331 15.345572,13.0825545 15.2691225,12.9922598 L12.3009997,9.48659872 C12.1225648,9.27584861 11.8070681,9.24965194 11.596318,9.42808682 C11.5752308,9.44594059 11.5556598,9.46551156 11.5378061,9.48659872 L8.56968321,12.9922598 C8.39124833,13.2030099 8.417445,13.5185067 8.62819511,13.6969416 C8.71848979,13.773391 8.8329684,13.8153448 8.95128003,13.8153448 Z" fill="#000000"/>
                                                         </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                </a>
                                            @endif

                                            @if(Auth::user()->hasRole('Employee'))
                                                <div class="btn-group">
                                                    <a href="{{isset($personel->file) ? $personel->file:null}}" download="" title="EVRAKLARI GÖSTER" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
                                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                               <polygon points="0 0 24 0 24 24 0 24"/>
                                                             <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                             <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                                             <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                                            </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                    </a>





                                                    @if(Auth::user()->employee_id == $personel->employee->id and $personel->zamane_accept == 1)
                                                        <a href="#" type="button" data-toggle="modal"    data-file="{{$personel->id}}" data-id="{{createHashId($personel->employee->id)}}" class="btn btn-warning btn-sm mr-3 accept_button">
                                                            <i class="flaticon-file"></i>Onay Ver</a>
                                                        <a href="#" type="button"  data-target="#protest" data-toggle="modal"  data-id="{{createHashId($personel->id)}}" class="btn btn-danger btn-sm mr-3 protestButton">
                                                            <i class="flaticon-file"></i>İtiraz Et</a>
                                                    @endif
                                                    @if($personel->zamane_accept == 0)
                                                            <a href="#" class="btn btn-danger btn-sm mr-3 protestForm" data-id="{{$personel->protest($personel->employee_id)}}" >
                                                                <i class="flaticon2-sms"></i>İtiraz Formu</a>
                                                        @endif
                                                </div>
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

        @include('personelFiles.InsertModal.fileType')
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

            $('body').on('click', '.protestButton', function() {
            var file_id = $(this).data('id');
            $(".notification_id3").val(file_id);
            $('body').on('click', '.protest_submit', function() {

                $("#protestForm").submit();
            });


        });
    </script>
    <script>
        $(".fileUploadId").click(function ()
        {
           var id = $(this).data('id');
           $(".fileUpload").val(id);
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
        $('body').on('click', '.accept_button', function() {

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

    <script>
        $('body').on('click', '.protestForm', function() {
            var id = $(this).data('id');

            $("#protestForms"+ id).modal('show');
        });

    </script>
@endpush


