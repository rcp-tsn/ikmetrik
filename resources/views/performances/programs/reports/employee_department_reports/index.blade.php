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
                                    <span class="d-block text-dark font-weight-bolder">Katıldığınız Performanslar</span>
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
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Dönem Adı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Başlangıç Tarihi / Bitiş Tarihi </th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Değerlendirme Süresi Bitiş Tarihi</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Kalan Gün</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Katılımcı Sayısı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Durum</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">İşlemler</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($performances as $performance)
                                    <tr>
                                        <td style="font-weight: bold;font-size: 15px">{{$performance->name}}
                                        </td>
                                        <td style="font-weight: bold;font-size: 15px">{{ $performance->start_date->format('d/m/Y') }} - {{ $performance->finish_date->format('d/m/Y') }} </td>
                                        <td style="font-weight: bold;font-size: 15px">{{ $performance->target_finish_date->format('d/m/Y') }}</td>
                                        <td style="font-weight: bold;font-size: 15px">{{ isset($dayFark[$performance->id]) ? $dayFark[$performance->id] : null  }} Gün</td>
                                        <td style="font-weight: bold;font-size: 15px">{{$performance->applicantCount($performance->id)}}</td>
                                        <td>
                                            @if(isset($dayFark[$performance->id]))
                                                @if($dayFark[$performance->id] >=0)
                                                    <span class="label label-lg label-light-warning label-inline font-weight-bold py-4" style="font-weight: bold;font-size: 15px;color: black">Program Devam Ediyor</span>
                                                @else
                                                    <span class="label label-lg label-light-success label-inline font-weight-bold py-4" style="font-weight: bold;font-size: 15px;color: black">Program Tmamlandı</span>
                                                @endif
                                            @endif

                                        </td>
                                        <td nowrap="nowrap">
                                            <a href="{{ route('department_employee_report_show', createHashId($performance->id)) }}" title="DEPARTMAN RAPORU GÖR" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button-area">
																<span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\legacy\metronic\theme\html\demo10\dist/../src/media/svg/icons\Design\Substract.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                            </a>

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
@endpush

