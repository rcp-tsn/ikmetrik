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
                                    <span class="d-block text-dark font-weight-bolder">Bordro Raporlama</span>
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
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">#</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Adı/Soyadı</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Gönderilme Tarihi</th>
                                    <th style="background-color: darkorange;font-weight: bold;font-size: 15px">Kabul Etme Tarihi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $sira = 1; @endphp
                                @foreach($calenders as $calender)
                                    <tr>
                                        <td style="font-weight: bold;font-size: 15px">{{$sira ++}}</td>
                                        <td style="font-weight: bold;font-size: 15px">{{!empty($calender->employee()) ? $calender->employee() : null}}</td>
                                        <td style="font-weight: bold;font-size: 15px">{{!empty($calender->sms_date) ? $calender->sms_date->format('d/m/Y H:i:s') : ' '}}</td>
                                        <td style="font-weight: bold;font-size: 15px">{{!empty($calender->accept_date) ? $calender->accept_date->format('d/m/Y H:i:s') : ' '}}</td>
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

