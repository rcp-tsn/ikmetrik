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
                        <div class="card-header h-auto border-0 bg-success">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-white font-weight-bolder">{{ $donem }} Hakedişleri Çekilen Firmalar</span>
                                </h3>
                            </div>
                            <div class="card-toolbar">

                                <div class="form-group">
                                    <select class="form-control form-control-lg" id="dateSelect">
                                        <option>Görüntülemek istediğiniz ayı seçiniz</option>
                                        @foreach($select_date as $key => $select)
                                        <option value="{{ $select }}" {{ $select == $accrual ? 'selected' : '' }}>{{ $select }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <table class="table table-bordered" id="kt_datatable">
                                <thead>
                                <tr>
                                    <th>Ay/Yıl</th>
                                    <th>Firma</th>
                                    <th>İşyeri Sicil</th>
                                    <th>Hak Ediş Durumu</th>
                                    <th>6111 S. Kanun</th>
                                    <th>17103 S. Kanun</th>
                                    <th>7252 S. Kanun</th>
                                    <th>Toplam Tutar</th>
                                    <th>Hak Ediş Oranı</th>
                                    <th>More Payroll Hakediş</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($sgk_companies as $sgk_company)
                                    @php
                                        $result_array = $sgk_company->getHakedis($accrual);
                                    @endphp
                                    <tr>
                                        <td>{{ $donem }}</td>
                                        <td>{{ $sgk_company->name }}</td>
                                        <td>{{ $sgk_company->registry_id }}</td>
                                        <td>ÇEKİLDİ</td>
                                        <td>{!! number_format($result_array['total_law_6111'], 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($result_array['total_law_27103'], 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($result_array['total_law_7252'], 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($result_array['total_law_6111'] + $result_array['total_law_27103'] + $result_array['total_law_7252'], 2, ',', '.') !!} TL</td>
                                        <td>%{{ $sgk_company->company_gain_ratio }}</td>
                                        <td>{!! number_format((($result_array['total_law_6111'] + $result_array['total_law_27103'] + $result_array['total_law_7252']) * $sgk_company->company_gain_ratio) / 100 , 2, ',', '.') !!} TL</td>


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
            <br/>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0 bg-danger">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-white font-weight-bolder">{{ $donem }} Hakedişleri Çekilmeyen Firmalar</span>
                                </h3>
                            </div>


                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            <table class="table table-bordered" id="kt_datatable2">
                                <thead>
                                <tr>
                                    <th>Ay/Yıl</th>
                                    <th>Firma</th>
                                    <th>İşyeri Sicil</th>
                                    <th>Hak Ediş Durumu</th>

                                </tr>

                                </thead>
                                <tbody>
                                @foreach($un_sgk_companies as $sgk_company)
                                    <tr>
                                        <td>{{ $donem }}</td>
                                        <td>{{ $sgk_company->name }}</td>
                                        <td>{{ $sgk_company->registry_id }}</td>
                                        <td>ÇEKİLMEDİ</td>
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

        $(function(){
            // bind change event to select
            $('#dateSelect').on('change', function () {
                var accrual = $(this).val(); // get selected value
                if (accrual) { // require a URL
                    window.location = '/control-gain-incentives/'+ accrual; // redirect
                }
                return false;
            });
        });
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
            var init2 = function() {
                var table = $('#kt_datatable2');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: true
                });

                $('#kt_datatable2_search_status, #kt_datatable2_search_type').selectpicker();
            };
            return {

                //main function to initiate the module
                init: function() {
                    init();
                    init2();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesAdvancedColumnRendering.init();
        });
    </script>

    <script>
        'use strict';
        $(document).ready(function () {

            $(document).on('click', 'a.page-tour', function () {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([
                    {
                        'next .assignment-button-area': 'Sisteme kayıt ettiğiniz kullanıcılara bu bölümden SGK şube ataması yapabilirsiniz. ',
                    }
                ]);
                enjoyhint_instance.run();
            });

        });
    </script>
@endpush



