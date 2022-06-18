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
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole('e-bordro'))

                        <span class="label label-lg label-light-warning label-inline font-weight-bold py-4 text-black">Onayınız Bekleyen : {{isset($warning_pdks) ? $warning_pdks : 0}}</span>

                        <span class="label label-lg label-light-danger label-inline font-weight-bold py-4 ml-3 text-black">Reddedilen : {{isset($danger_pdks) ? $danger_pdks : 0}} </span>

                        <span class="label label-lg label-light-success label-inline font-weight-bold py-4 ml-3 text-black">Onaylanan : {{isset($accept_pdks) ? $accept_pdks : 0}}</span>

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
                                    <span class="d-block text-dark font-weight-bolder">PUANTAJLARIM</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')

                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @php $sira = 1; @endphp
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>İSİM</th>
                                    <th>SOYİSİM</th>
                                    <th>ONAY DURUMU</th>
                                    <th>Puantaj</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pdks as $pdk)

                                    <tr>
                                        <td>{{!empty($pdk->employee) ? $pdk->employee->first_name : null}}</td>
                                        <td>{{!empty($pdk->employee) ? $pdk->employee->last_name : null}}</td>
                                        <td>
                                            @if($pdk->zamane_accept == 0)
                                                <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Onayınız Bekleniyor</span>
                                            @elseif($pdk->zamane_accept == 2)
                                                <span class="label label-lg label-light-danger label-inline font-weight-bold py-4">Reddedildi</span>
                                            @else
                                                <span class="label label-lg label-light-success label-inline font-weight-bold py-4">Onaylandı</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if( Auth::user()->hasRole('e-bordro') and $pdk->zamane_accept == 2)
                                                <a href="#"  data-id="{{$pdk->pdk_id}}" data-employee="{{createHashId($pdk->employee->id)}}" data-page="{{$pdk->page}}" title="Düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area editModal">
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

                                            @endif

                                            @if($pdk->zamane_accept == 0 and Auth::user()->hasRole('Employee'))
                                                <div class="btn-group">
                                                    <a href="{{isset($pdk->file) ? $pdk->file:null}}" download="" title="PUANTAJLARI GÖSTER" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
                                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                               <polygon points="0 0 24 0 24 24 0 24"/>
                                                             <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                             <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                                             <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                                            </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                    </a>
                                                    @if(Auth::user()->employee_id == $pdk->employee->id)
                                                    <a href="#" type="button" data-toggle="modal"   data-page="{{$pdk->page}}" data-bordro="{{$pdk->id}}" data-id="{{createHashId($pdk->employee->id)}}" class="btn btn-warning btn-sm mr-3 accept_button">
                                                        <i class="flaticon-file"></i>Onay Ver</a>
                                                    <a href="#" type="button"  data-target="#protest" data-toggle="modal" data-page="{{$pdk->page}}"  data-bordro="{{$pdk->pdks->id}}" data-id="{{createHashId($pdk->employee->id)}}" class="btn btn-danger btn-sm mr-3 protestButton">
                                                        <i class="flaticon-file"></i>İtiraz Et</a>
                                                    @endif
                                                @else
                                                    @if($pdk->zamane_accept == 0 or $pdk->zamane_accept == 2 )
                                                            <a href="{{isset($pdk->file) ? '/company_pdk'.$pdk->file:null}}" download="" title="PUANTAJLARI GÖSTER" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
                                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                               <polygon points="0 0 24 0 24 24 0 24"/>
                                                             <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                             <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                                             <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                                            </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                            </a>
                                                    @else
                                                            <a href="{{isset($pdk->file) ? $pdk->file:null}}" download="" title="BORDROLARI GÖSTER" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
                                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\File.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                               <polygon points="0 0 24 0 24 24 0 24"/>
                                                             <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                             <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                                                             <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                                                            </g>
                                                        </svg><!--end::Svg Icon--></span>
                                                            </a>
                                                    @endif
                                                    @if($pdk->zamane_accept == 2)
                                                            <a href="#" class="btn btn-danger btn-sm mr-3 protestForm" data-id="{{$pdk->protest($pdk->employee_id)}}" >
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
                   @include('pdks.insertModal')
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
        $('.protestButton').click(function () {
            var working_id = $(this).data('id');
            var payrol_id = $(this).data('bordro');
            $(".payroll_id3").val(payrol_id);
            $(".working_id3").val(working_id);
            var page = $(this).data('page');
            $(".page2").val(page);
        });
    </script>


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
        $(".Sil").click(function ()
        {
            var id = $(this).data('id');


            swal({
                title: "Emin misiniz?",
                text: "Silme İşlemi Geri Alınamaz! Yapılan Tüm Süreç Silinecek!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {

                        window.location.href="{{env('APP_URL')}}/performance/delete/"+id;

                        swal("Silme İşlemi Başarılı", {
                            icon: "success",
                        });
                    } else {
                        swal("Silme İşlemi Başarısız");
                    }
                });
        });
    </script>

    <script>
        $('.sms').click(function () {
            var id = $(this).data('id');
            console.log(id);
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem Geri alınamaz Bordrolar Kontrol Edildikten Sonra Yapınız!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $('.loading2').show();
                        window.location.href = '{{env('APP_URL')}}/employee/payrolls/sms/'+id;
                    } else {
                        swal("Silme İşlemi İptal Edildi");
                        $(".page").show();
                    }
                });
        });

    </script>

    <script>

        $('.payrollDelete').click(function () {
            var id = $(this).data('id');
            console.log(id);
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem Geri Alınamaz Silme İşlemi İçin Onaylayın!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $("#payrolldelete"+id).submit();
                        swal("Silme İşlemi Başarılı", {
                            icon: "success",
                        });
                    } else {
                        swal("Silme İşlemi İptal Edildi");
                    }
                });
        });

    </script>

    <script>
        $('body').on('click', '.accept_button', function() {
            //code

            var working_id = $(this).data('id');
            var payrol_id = $(this).data('bordro');
            var page = $(this).data('page');
            $(".payroll_i2").val(payrol_id);
            $(".working_i2").val(working_id);
            $(".page").val(page);

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
                                $('#modalFillIn').modal('show');
                                $('.code').click(function () {


                                    $("#payroll_accept_employee").submit();
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
        $(".editModal").click(function ()
        {
            var payroll_id = $(this).data('id');
            $(".editPayrollId").val(payroll_id);
            var employee_id = $(this).data('employee');
            $(".editEmployeeId").val(employee_id);
            var page_id = $(this).data('page');
            $(".editPageId").val(page_id);
            $("#editModal").modal('show');
        });
    </script>
    <script>
            $(".protestForm").click(function ()
            {
                var id = $(this).data('id');

                $("#protestForms"+ id).modal('show');
            });

    </script>
@endpush

