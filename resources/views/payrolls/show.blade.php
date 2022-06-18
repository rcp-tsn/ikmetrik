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

                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4 text-black">Onayınız Bekleyen : {{isset($warning_payrolls) ? $warning_payrolls : 0}}</span>

                    <span class="label label-lg label-light-danger label-inline font-weight-bold py-4 ml-3 text-black">Reddedilen : {{isset($danger_payrolls) ? $danger_payrolls : 0}} </span>

                    <span class="label label-lg label-light-success label-inline font-weight-bold py-4 ml-3 text-black">Onaylanan : {{isset($accept_payrolls) ? $accept_payrolls : 0}}</span>

                    <a href="{{route('payroll_zip',createHashId($id))}}" title="TÜM BORDROLARI ÇIKTI AL" class="btn-icon btn-lg"><span class="svg-icon svg-icon-4x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Files\Downloads-folder.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
        <path d="M14.8875071,12.8306874 L12.9310336,12.8306874 L12.9310336,10.8230161 C12.9310336,10.5468737 12.707176,10.3230161 12.4310336,10.3230161 L11.4077349,10.3230161 C11.1315925,10.3230161 10.9077349,10.5468737 10.9077349,10.8230161 L10.9077349,12.8306874 L8.9512614,12.8306874 C8.67511903,12.8306874 8.4512614,13.054545 8.4512614,13.3306874 C8.4512614,13.448999 8.49321518,13.5634776 8.56966458,13.6537723 L11.5377874,17.1594334 C11.7162223,17.3701835 12.0317191,17.3963802 12.2424692,17.2179453 C12.2635563,17.2000915 12.2831273,17.1805206 12.3009811,17.1594334 L15.2691039,13.6537723 C15.4475388,13.4430222 15.4213421,13.1275254 15.210592,12.9490905 C15.1202973,12.8726411 15.0058187,12.8306874 14.8875071,12.8306874 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span></a>
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
                                    <span class="d-block text-dark font-weight-bolder">Bordrolarım</span>
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
                                    <th>İSİM SOYİSİM</th>
                                    <th>BORDRO</th>
                                    <th>ONAY DURUMU</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payrolls as $payroll)
                                    @php $employee_id =  isset($payroll->employee) ? $payroll->employee->id : 0 @endphp

                                    <tr>
                                        <td>{{!empty($payroll->employee) ? $payroll->employee->first_name : null}} {{!empty($payroll->employee) ? $payroll->employee->last_name : null}}</td>

                                        <td>
                                            @if( Auth::user()->hasRole('e-bordro') and $payroll->zamane_accept != 1)
                                                <a href="#"  data-id="{{$payroll->payroll_id}}" data-employee="{{isset($payroll->employee->id) ? createHashId($payroll->employee->id) : 0}}" data-page="{{$payroll->page}}" title="Düzenle" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area editModal">
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

                                                <a href="{{route('One_employee_sms',['id'=>createHashId($payroll->payroll_id),'employee_id'=>createHashId($payroll->employee_id)])}}" title="Tekrar Sms Gönder" class="btn btn-warning btn-sm mr-3">
                                                    <i class="flaticon-file"></i>Tekrar SMS Gönder</a>

                                            @endif

                                            @if($payroll->zamane_accept == 0 and Auth::user()->hasRole('Employee'))
                                                <div class="btn-group">
                                                    <a href="{{isset($payroll->file) ? '/company_payrolls'.$payroll->file:null}}" target="_blank" title="BORDROLARI GÖSTER" class="btn btn-outline-success btn-sm mr-3">
                                                        <i class="flaticon2-poll-symbol"></i>BORDRO GÖSTER</a>

                                                    @if(Auth::user()->employee_id == $employee_id)
                                                        <a href="javascript:void(0);" type="button" data-toggle="modal"  data-target="#modalFillIn"  data-page="{{$payroll->page}}" data-bordro="{{$payroll->id}}" data-id="{{isset($payroll->employee) ? createHashId($payroll->employee->id) : 0}}" class="btn btn-warning btn-sm mr-3 accept_button">
                                                            <i class="flaticon-file"></i>Onay Ver</a>
                                                        <a href="javascript:void(0);" type="button"  data-target="#protest" data-toggle="modal" data-page="{{$payroll->page}}"  data-bordro="{{$payroll->payroll->id}}" data-id="{{isset($payroll->employee) ? createHashId($payroll->employee->id) : 0}}" class="btn btn-danger btn-sm mr-3 protestButton">
                                                            <i class="flaticon-file"></i>İtiraz Et</a>
                                                    @endif

                                                    @else
                                                        @if($payroll->zamane_accept == 0 or $payroll->zamane_accept == 2 )
                                                                <a href="{{isset($payroll->file) ? '/company_payrolls'.$payroll->file:null}}"  target="_blank" title="BORDROLARI GÖSTER" class="btn btn-outline-success btn-sm mr-3">
                                                                <i class="flaticon2-poll-symbol"></i>BORDRO GÖSTER</a>
                                                        @else

                                                            <a href="{{isset($payroll->file) ? $payroll->file:null}}" title="BORDROLARI GÖSTER"  target="_blank" class="btn btn-outline-success btn-sm mr-3">
                                                                <i class="flaticon2-poll-symbol"></i>BORDRO GÖSTER</a>
                                                        @endif
                                                        @if($payroll->zamane_accept == 2)
                                                            <a href="#" class="btn btn-danger btn-sm mt-5 mr-3 protestForm" data-id="{{$payroll->protest($payroll->employee_id)}}" >
                                                                <i class="flaticon2-sms"></i>İtiraz Formu</a>
                                                        @endif
                                                </div>
                                            @endif

                                        </td>

                                        <td>
                                            @if($payroll->zamane_accept == 0)
                                                <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Onayınız Bekleniyor</span>
                                            @elseif($payroll->zamane_accept ==2)
                                                <span class="label label-lg label-light-danger label-inline font-weight-bold py-4">Reddedildi</span>
                                            @else
                                                <span class="label label-lg label-light-success label-inline font-weight-bold py-4">Onaylandı</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
                                        <h5 class="modal-title" id="exampleModalLabel">Onaylama işleminiz yapılıyor</h5>
                                    </div>
                                    <div class="modal-body">
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
                        <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                    @include('payrolls.insertModal')
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
        $('body').on('click','.protestButton',function ()
        {
            var working_id = $(this).data('id');
            console.log(working_id);
            var payrol_id = $(this).data('bordro');
            $(".payroll_id3").val(payrol_id);
            $(".working_id3").val(working_id);
            var page = $(this).data('page');
            $(".page2").val(page);


            $('.protest_submit').click(function ()
            {
                $("#protestForm").submit();
            });


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
            $('body').on('click','.payrollDelete',function (){
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
        $('body').on('click','.accept_button',function ()
        {
            var working_id = $(this).data('id');
            var payrol_id = $(this).data('bordro');
            var page = $(this).data('page');
            $(".payroll_i2").val(payrol_id);
            $(".working_i2").val(working_id);
            $(".page").val(page);


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
                        $('#modalFillIn').modal('hide');
                        $('#LoadingModal').modal('show');

                    });
                },
                error: function () {
                }
            });

            // swal({
            //     title: "Eminmisiniz?",
            //     text: " Onaylayın!",
            //     icon: "warning",
            //     buttons: true,
            //     dangerMode: true,
            // })
            //     .then((willDelete) => {
            //         if (willDelete) {
            //
            //
            //         } else
            //         {
            //             swal("Onay İşlemi İptal Edildi");
            //         }
            //     });





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

