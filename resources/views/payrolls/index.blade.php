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
                    <a href="{{ route('payroll_pdf_upload') }}" class="btn btn-primary font-weight-bold btn-sm create-button-area">
    <span class="svg-icon svg-icon-light svg-icon-2x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span> BORDRO YÜKLE
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
                                    <th>#</th>
                                    <th>Tarih</th>
                                    <th>Dönem</th>
                                    @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                        <th>Çalışan Sayısı</th>
                                        <th>Gönder</th>
                                        <th>İşlemler</th>
                                    @endif
                                    <th>Bordrolar</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $sira = 1; @endphp
                                @foreach($payrolls as $payroll)
                                    <tr>
                                        <td>{{$sira ++}}</td>
                                        <td>{{!empty($payroll->date) ? $payroll->date->format('d/m/Y') : null}}</td>
                                        <td>{{isset($period[$payroll->id]) ? $period[$payroll->id] : ' '}}</td>

                                        @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                            <td>{{!empty($payroll->employee_count) ? $payroll->employee_count : null}}</td>
                                            <td>
                                                @if($payroll->sms_status==1)
                                                    <span class="label label-lg label-light-success label-inline font-weight-bold py-4 ">SMS GÖNDERİLDİ</span>
                                                @else
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">GÖNDERİM BEKLİYOR</span>
                                                    <br>
                                                    <a style="margin-top: 10px" title="TÜM PERSONELLERE SMS GÖNDER"  data-id="{{$payroll->id}}" class="btn btn-outline-success btn-sm mr-3 sms">
                                                        <i class="flaticon2-chat-1"></i>Gönder</a>

                                                @endif
                                            </td>
                                            <td>
                                                @if($payroll->sms_status == 0 || \Illuminate\Support\Facades\Auth::user()->hasRole('Admin'))

                                                    <form  id="payrolldelete{{ $payroll->id }}" action="{{route('payroll.delete',['id' => createHashId($payroll->id)])}}" method="POST">
                                                        @csrf
                                                        <a href="#" type="button" data-id="{{$payroll->id}}" class="btn btn-outline-danger btn-sm mr-3 payrollDelete">
                                                            <i class="flaticon2-trash"></i>Sil</a>
                                                    </form>
                                                @else
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4">Gönderilen Bordrolar Silinemez</span>

                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{route('employee_payroll_show',['id'=>createHashId($payroll->id)])}}" class="btn btn-success btn-sm mr-3">
                                                    <i class="flaticon2-pie-chart"></i>Bordroları Gör</a>
                                                @if(Auth::user()->hasAnyRole('Admin','e-bordro'))
                                                    <a href="{{route('payroll_not',createHashId($payroll->id))}}" class="btn btn-warning btn-sm mr-3">
                                                        <i class="flaticon2-pie-chart"></i>Eşleşmeyenler</a>
                                                    <a href="{{route('personelReport.show',createHashId($payroll->id))}}" class="btn btn-primary mr-3">
                                                        <i class="flaticon2-box-1"></i>Raporlama</a>

                                            </div>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


                            <div class="modal fade" id="LoadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-xl"  role="document">
                                    <div class="modal-content ">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Bordrolar Gönderiliyor</h5>
                                        </div>
                                        <div class="modal-body">
                                            <h3>Lütfen Bekleyiniz</h3>
                                            <div class="row">
                                                    <div class="card card-body border-top-1 border-top-pink justify-content-center align-items-center">
                                                        <div class="col-lg-6">
                                                            <div class="progress" style="height: 4rem;font-size: 1.5rem;">
                                                                <div class="progress-bar progress-bar-striped bg-warning" style="width: 10%">
                                                                    <span id="step-info">0%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="timeline timeline-5 mt-3 list-feed" style="overflow-y: scroll;max-height: 100px;">
                                                                <div class="timeline-item align-items-start list-feed-item">
                                                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">2</div>
                                                                    <div class="timeline-badge">
                                                                        <i class="fa fa-genderless text-success icon-xxl"></i>
                                                                    </div>
                                                                    <div class="timeline-content text-dark-50">
                                                                        Başlatılma Bekleniyor.
                                                                    </div>
                                                                </div>
                                                                <div class="timeline-item align-items-start list-feed-item">
                                                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">1</div>
                                                                    <div class="timeline-badge">
                                                                        <i class="fa fa-genderless text-success icon-xxl"></i>
                                                                    </div>
                                                                    <div class="timeline-content text-dark-50">
                                                                        Sistem hazır.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
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

                        window.location.href= "{{env('APP_URL')}}/performance/delete/"+id;


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
                        swal("Gönderim İşlemi İptal Edildi");
                    }
                });
        });

    </script>
    <script>
        var steps = {

            'PayrollSms': {
                url: '{!! route('payrolls.PayrollSms') !!}',
                loginurl: '',
            },

        };
        var stepManager = {
            currentStep: 'PayrollSms',
            work: function (step) {
                axios.get(steps[step].url).then(function (response) {
                    if (response.data) {
                        if (response.data.code == 'LOGIN_FAIL') {
                            console.log(steps[step].url+"|data key|"+response.data.key + "|step|"+ step);
                            //stepManager.currentStep = step;
                            //stepManager.work(response.data.step);
                            loginModal(steps[step].loginurl, response.data.key, step);
                        } else {
                            console.log('response.data.step');
                            console.log(response.data.step)
                            if (response.data.code == 'SUCCESS') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html(response.data.progress + "%");
                                $(".progress-bar").css('width', response.data.progress + '%');
                                stepManager.work(response.data.step);
                                stepManager.currentStep = response.data.step;
                            } else if (response.data.code == 'TIMEOUT') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html(response.data.progress + "%");
                                $(".progress-bar").css('width', response.data.progress + '%');
                                stepManager.work(response.data.step);
                                stepManager.currentStep = response.data.step;
                            } else if (response.data.code == 'FINISH') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html(response.data.progress + "%");
                                $(".progress-bar").css('width', response.data.progress + '%');
                                returnList(response.data.url);
                            } else if (response.data.code == 'ERROR') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html("100%");
                                $(".progress-bar").css('width', '100%');
                            } else {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html("İşlem Durduruldu!");
                            }
                        }
                    }
                }).catch(function (error) {
                    $("#step-info").html("İşlem Durduruldu!");
                    console.log('203');
                    console.log(error);
                });
            },
            nextStep: function () {
            },
            advance: function () {
                console.log('210');
                console.log('de:'+stepManager.currentStep);
                stepManager.work(stepManager.currentStep);

            }
        };


        function returnList(url) {
            $(".card-body").html("Yönlendiriliyorsunuz...");
            setTimeout(function () {
                window.location.assign(url);
            }, 3000);
        }


        $('.sms').click(function () {
            var id = $(this).data('id');
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem Geri alınamaz Bordrolar Kontrol Edildikten Sonra Yapınız!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $('#LoadingModal').modal('show');
                        $.ajax({
                            type: "GET",
                            url: '/employeeSmsId/'+id,
                            success: function (response) {
                                if (response.code == 'SUCCESS') {
                                    stepManager.work('PayrollSms');
                                    $("#step-info").html(10 + "%");
                                    $(".progress-bar").css('width', 10 + '%');
                                } else {
                                    stepManager.advance();
                                    $("#step-info").html("Hatalı İŞLEM!");
                                }

                            },

                            error: function (error) {
                                $("#step-info").html("Hatalı Giriş!");
                                console.log(error);
                            }
                        });

                    } else {
                        swal(" İşlem İptal Edildi");
                        $(".page").show();
                    }
                });
        });

    </script>

@endpush
