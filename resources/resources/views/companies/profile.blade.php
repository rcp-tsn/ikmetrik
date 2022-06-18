@extends('layouts.app')
@section('content')
    @if(Auth::user()->company_id == 12)
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <!--begin::Details-->
                        <div class="d-flex mb-9">
                            <!--begin: Pic-->
                            <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                                <div class="symbol symbol-50 symbol-lg-120">
                                    <img src="{{ $owner->pictureLink }}" alt="image">
                                </div>
                                <div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
                                    <span class="font-size-h3 symbol-label font-weight-boldest">{{ $owner->name }}</span>
                                </div>
                            </div>
                            <!--end::Pic-->
                            <!--begin::Info-->
                            <div class="flex-grow-1">
                                <!--begin::Title-->
                                <div class="d-flex justify-content-between flex-wrap mt-1">
                                    <div class="d-flex mr-3">
                                        <a href="#" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{ $owner->name }}</a>
                                        <a href="javascript:void(0);">
                                            <i class="flaticon2-correct text-success font-size-h5"></i>
                                        </a>
                                    </div>
                                </div>
                                <!--end::Title-->
                                <!--begin::Content-->
                                <div class="d-flex flex-wrap justify-content-between mt-1">
                                    <div class="d-flex flex-column flex-grow-1 pr-8">
                                        <div class="d-flex flex-wrap mb-4">
                                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                <i class="flaticon2-new-email mr-2 font-size-lg"></i>{{ $owner->email }}</a>
                                        </div>
                                        <span class="font-weight-bold text-dark-50">{{ $company->name }} Yetkilisi</span>
                                        <span class="font-weight-bold text-dark-50">Firma hakkında kısa bir bilgi alanı güncellenecek....</span>
                                    </div>
                                    <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                                        <span class="font-weight-bold text-dark-75">Progress</span>
                                        <div class="progress progress-xs mx-3 w-100">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="font-weight-bolder text-dark">78%</span>
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Details-->
                        <div class="separator separator-solid"></div>
                        <!--begin::Items-->
                        <div class="d-flex align-items-center flex-wrap mt-8">
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-piggy-bank display-4 text-muted font-weight-bold"></i>
												</span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Kullanıcı</span>
                                    <span class="font-weight-bolder font-size-h5">
													<span class="text-dark-50 font-weight-bold"></span>{{ count($company_users) }} kişi</span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
												</span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">Çalışan</span>
                                    <span class="font-weight-bolder font-size-h5">
													<span class="text-dark-50 font-weight-bold"></span>{{ $company->getTotalEmployee() }} kişi</span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
												</span>
                                <div class="d-flex flex-column text-dark-75">
                                    <span class="font-weight-bolder font-size-sm">SGK Şube</span>
                                    <span class="font-weight-bolder font-size-h5">
													<span class="text-dark-50 font-weight-bold"></span>{{ $sgk_company_count }} şube</span>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-file-2 display-4 text-muted font-weight-bold"></i>
												</span>
                                <div class="d-flex flex-column flex-lg-fill">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">İş Kazası</span>
                                    <a href="javascript:void(0);" title="Aktif Ay / Tüm Zamanlar" class="text-primary font-weight-bolder">{{ $company->getKaza('ay') }}/{{ $company->getKaza('toplam') }}</a>

                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-chat-1 display-4 text-muted font-weight-bold"></i>
												</span>
                                <div class="d-flex flex-column">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">Rapor Sayısı</span>
                                    <a href="javascript:void(0);" title="Aktif Ay / Tüm Zamanlar" class="text-primary font-weight-bolder">{{ $company->getRapor('ay') }}/{{ $company->getRapor('toplam') }}</a>
                                </div>
                            </div>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <div class="d-flex align-items-center flex-lg-fill mb-2 float-left">
												<span class="mr-4">
													<i class="flaticon-network display-4 text-muted font-weight-bold"></i>
												</span>
                                <div class="symbol-group symbol-hover">
                                    @php $i = 1; @endphp
                                    @foreach($company_users as $u)
                                        @if($i <= 5)
                                            <div class="symbol symbol-30 symbol-circle" data-toggle="tooltip" title="" data-original-title="{{ $u->name }}">
                                                <img alt="{{ $u->name }}" src="{{ $u->pictureLink }}">
                                            </div>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                    @if(count($company_users) > 5)
                                        <div class="symbol symbol-30 symbol-circle symbol-light">
                                            <span class="symbol-label font-weight-bold">{{ count($company_users) - 5 }}+</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!--end::Item-->
                        </div>
                        <!--begin::Items-->
                    </div>
                </div>
                <!--end::Card-->
                <!--begin::Row-->
                @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Metrik') || Auth::user()->modulePermit(2))
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Advance Table Widget 2-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Çalışan Sayıları</span>
                                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Firmanızdaki aylara göre çalışan sayıları{{ $company->start_date }}</span>
                                </h3>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <thead>
                                        <tr>
                                            <th class="p-0 w-40px"></th>
                                            <th class="p-0 min-w-200px"></th>
                                            <th class="p-0 min-w-100px"></th>
                                            <th class="p-0 min-w-125px"></th>
                                            <th class="p-0 min-w-110px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($total_staffs as $total_staff)
                                        <tr>
                                            <td class="pl-0 py-4">
                                                <div class="symbol symbol-50 symbol-light">
																				<span class="symbol-label">
																					<img src="/assets/media/svg/misc/003-puzzle.svg" class="h-50 align-self-center" alt="">
																				</span>
                                                </div>
                                            </td>
                                            <td class="pl-0">
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Toplam Çalışan Sayısı</a>
                                                <div>
                                                    <span class="font-weight-bolder">{{ $total_staff->staff }}</span>
                                                </div>
                                            </td>

                                            <td class="text-right">
                                                @php $d = explode('-', $total_staff->accrual);@endphp
                                                <span class="text-muted font-weight-500">{{ getFullMonthName($d[1]) }} {{ $d[0] }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ number_format($company->getPrice($total_staff->staff) ,2 ,',' ,'.') }} TL</span>
                                                <span class="text-muted font-weight-bold">Çalışan sayısı x {{ number_format($company->getStafPrice($total_staff->staff) ,2 ,',' ,'.') }} TL</span>
                                            </td>
                                            <td class="text-right">
                                                <span class="label label-lg label-light-success label-inline">BEKLEMEDE</span>
                                            </td>
                                            <td class="text-right pr-0">
                                                <a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
																				<span class="svg-icon svg-icon-md svg-icon-primary">
																					<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/General/Settings-1.svg-->
																					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																							<rect x="0" y="0" width="24" height="24"></rect>
																							<path d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z" fill="#000000"></path>
																							<path d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z" fill="#000000" opacity="0.3"></path>
																						</g>
																					</svg>
                                                                                    <!--end::Svg Icon-->
																				</span>
                                                </a>
                                                <a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3">
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
                                                <a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm">
																				<span class="svg-icon svg-icon-md svg-icon-primary">
																					<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/General/Trash.svg-->
																					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																							<rect x="0" y="0" width="24" height="24"></rect>
																							<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
																							<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"></path>
																						</g>
																					</svg>
                                                                                    <!--end::Svg Icon-->
																				</span>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Advance Table Widget 2-->
                    </div>
                </div>
                <!--end::Row-->
                @endif
                <!--begin::Row-->
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Charts Widget 4-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header h-auto border-0">
                                <div class="card-title py-5">
                                    <h3 class="card-label">
                                        <span class="d-block text-dark font-weight-bolder">Çalışan Sayıları</span>
                                        <span class="d-block text-muted mt-2 font-size-sm">Aylara göre çalışan sayıları</span>
                                    </h3>
                                </div>
                                <div class="card-toolbar">

                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body" style="position: relative;">
                                <div id="kt_amcharts_1" style="height: 500px;"></div>
                            </div>

                            <!--end::Body-->
                        </div>
                        <!--end::Charts Widget 4-->
                    </div>

                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    @endif
@stop

@push('scripts')
    <script>
        "use strict";

        // Class definition
        var KTamChartsChartsDemo = function() {

            // Private functions
            var demo1 = function() {
                var chart = AmCharts.makeChart("kt_amcharts_1", {
                    "rtl": KTUtil.isRTL(),
                    "type": "serial",
                    "theme": "light",
                    "dataProvider": [{!!  $chart_text !!}],
                    "valueAxes": [{
                        "gridColor": "#FFFFFF",
                        "gridAlpha": 0.2,
                        "dashLength": 0
                    }],
                    "gridAboveGraphs": true,
                    "startDuration": 1,
                    "graphs": [{
                        "balloonText": "[[category]]: tarihinde <b>[[value]]</b> çalışanınız vardır.",
                        "fillAlphas": 0.8,
                        "lineAlpha": 0.2,
                        "type": "column",
                        "valueField": "visits"
                    }],
                    "chartCursor": {
                        "categoryBalloonEnabled": false,
                        "cursorAlpha": 0,
                        "zoomable": false
                    },
                    "categoryField": "country",
                    "categoryAxis": {
                        "gridPosition": "start",
                        "gridAlpha": 0,
                        "tickPosition": "start",
                        "tickLength": 20
                    },
                    "export": {
                        "enabled": true
                    }

                });
            }




            return {
                // public functions
                init: function() {
                    demo1();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTamChartsChartsDemo.init();
        });
    </script>
@endpush




