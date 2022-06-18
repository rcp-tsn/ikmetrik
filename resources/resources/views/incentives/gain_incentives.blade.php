@extends('layouts.app')
@section('content')

    @component('layouts.partials.subheader-v1', ['pdf_export' => $all ? '/pdf-exports/all-incentives-detailed/'.$sgk_company->id : '/pdf-exports/incentives-detailed/'.$sgk_company->id.'?date='.Request::get('date') ])
    @endcomponent
    @if($gain_incentive_first)
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            @include('partials.alerts.error')
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-xl-12">
                            <!--begin::Base Table Widget 1-->
                            <div class="card card-custom card-border">
                                <!--begin::Header-->
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <span class="card-label"><span class="font-weight-bold text-danger">{{ $all ? $company->name : $sgk_company->name }} {{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</span> 6111, 17103 ve 7252 Sayılı Kanunlardaki Teşvik Miktarları</span>
                                    </h4>
                                </div>
                                <div class="card-body pt-2 pb-0">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <thead>
                                                <tr>
                                                    <th class="p-0 w-50px"></th>
                                                    <th class="p-0 min-w-200px"></th>
                                                    <th class="p-0 min-w-100px"></th>
                                                    <th class="p-0 min-w-40px"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="pl-0 py-10">
                                                    <div class="symbol symbol-45 symbol-light-info mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-info">
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                                        <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                                        <circle fill="#000000" cx="12" cy="9" r="5"></circle>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Kanun No: 6111</a>
                                                    <span class="text-muted font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                @php $toplam_6111 = 0; @endphp
                                                @php $toplam_27103 = 0; @endphp
                                                @php $toplam_5510 = 0; @endphp
                                                @php $toplam_14857 = 0; @endphp
                                                @php $toplam_7252 = 0; @endphp
                                                @php $toplam_tutar = 0; @endphp

                                                @foreach($active_gains as $active_gain)
                                                    @php
                                                        $toplam_6111 += $active_gain->law_6111;
                                                        $toplam_5510 += $active_gain->law_5510;
                                                        $toplam_27103 += $active_gain->law_27103;
                                                        $toplam_7252 += $active_gain->law_7252;
                                                        $toplam_14857 += $active_gain->law_14857;
                                                        $toplam_tutar += $active_gain->total_amount;
                                                    @endphp
                                                @endforeach


                                                @php
                                                    $toplam = $toplam_6111 + $toplam_27103 + $toplam_14857 + $toplam_7252;
                                                    if ($toplam == 0) {
                                                       $yuzde_6111 = 0;
                                                       $yuzde_27103 = 0;
                                                       $yuzde_7252 = 0;
                                                       $yuzde_14857 = 0;
                                                    } else {
                                                       $yuzde_6111 = ($toplam_6111 * 100 ) / $toplam;
                                                       $yuzde_27103 = ($toplam_27103 * 100 ) / $toplam;
                                                       $yuzde_7252 = ($toplam_7252 * 100 ) / $toplam;
                                                       $yuzde_14857 = ($toplam_14857 * 100 ) / $toplam;
                                                    }
                                                    $chart_pie_toplam_6111 = $toplam_6111;
                                                    $chart_pie_toplam_5510 = $toplam_5510;
                                                    $chart_pie_toplam_27103 = $toplam_27103;
                                                    $chart_pie_toplam_7252 = $toplam_7252;
                                                    $chart_pie_toplam_14857 = $toplam_14857;
                                                    $chart_pie_toplam_tutar = $toplam_tutar;
                                                @endphp
                                                <td>
                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-muted mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_6111) }}%
                                                    </span>
                                                            <span class="text-muted font-size-md font-weight-bold">
                                                    {!! number_format($toplam_6111, 2, ',', '.') !!} TL
                                                    </span>
                                                        </div>
                                                        <div class="progress progress-xs w-100">
                                                            <div class="progress-bar bg-dark" role="progressbar" style="width: {{ intval($yuzde_6111) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-0 py-10">
                                                    <div class="symbol symbol-45 symbol-light-info mr-2">
                                        <span class="symbol-label">
                                            <span class="svg-icon svg-icon-2x svg-icon-info"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"></rect>
        <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <circle fill="#000000" cx="12" cy="9" r="5"></circle>
    </g>
</svg></span>                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Kanun No: 27103</a>
                                                    <span class="text-muted font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                <td>

                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-muted mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_27103) }}%
                                                    </span>
                                                            <span class="text-muted font-size-md font-weight-bold">
                                                        {!! number_format($toplam_27103, 2, ',', '.') !!} TL
                                                    </span>
                                                        </div>
                                                        <div class="progress progress-xs w-100">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ intval($yuzde_27103) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-0 py-10">
                                                    <div class="symbol symbol-45 symbol-light-info mr-2">
                                        <span class="symbol-label">
                                            <span class="svg-icon svg-icon-2x svg-icon-info"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"></rect>
        <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <circle fill="#000000" cx="12" cy="9" r="5"></circle>
    </g>
</svg></span>                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Kanun No: 7252</a>
                                                    <span class="text-muted font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                <td>

                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-muted mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_7252) }}%
                                                    </span>
                                                            <span class="text-muted font-size-md font-weight-bold">
                                                        {!! number_format($toplam_7252, 2, ',', '.') !!} TL
                                                    </span>
                                                        </div>
                                                        <div class="progress progress-xs w-100">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ intval($yuzde_27103) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-0 py-10">
                                                    <div class="symbol symbol-45 symbol-light-info mr-2">
                                        <span class="symbol-label">
                                            <span class="svg-icon svg-icon-2x svg-icon-info"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"></rect>
        <path d="M12,10.9996338 C12.8356605,10.3719448 13.8743941,10 15,10 C17.7614237,10 20,12.2385763 20,15 C20,17.7614237 17.7614237,20 15,20 C13.8743941,20 12.8356605,19.6280552 12,19.0003662 C11.1643395,19.6280552 10.1256059,20 9,20 C6.23857625,20 4,17.7614237 4,15 C4,12.2385763 6.23857625,10 9,10 C10.1256059,10 11.1643395,10.3719448 12,10.9996338 Z M13.3336047,12.504354 C13.757474,13.2388026 14,14.0910788 14,15 C14,15.9088933 13.7574889,16.761145 13.3336438,17.4955783 C13.8188886,17.8206693 14.3938466,18 15,18 C16.6568542,18 18,16.6568542 18,15 C18,13.3431458 16.6568542,12 15,12 C14.3930587,12 13.8175971,12.18044 13.3336047,12.504354 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <circle fill="#000000" cx="12" cy="9" r="5"></circle>
    </g>
</svg></span>                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">Kanun No: 14857</a>
                                                    <span class="text-muted font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                <td>

                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-muted mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_14857) }}%
                                                    </span>
                                                            <span class="text-muted font-size-md font-weight-bold">
                                                        {!! number_format($toplam_14857, 2, ',', '.') !!} TL
                                                    </span>
                                                        </div>
                                                        <div class="progress progress-xs w-100">
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ intval($yuzde_14857) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                            </div>
                            <!--end::Base Table Widget 1-->
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-xl-12">
                            <div class="card card-custom card-border">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h4 class="card-title">
                                            <span class="card-label"><span class="font-weight-bold text-danger">{{ $all ? $company->name : $sgk_company->name }} {{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</span> 6111-17103-7252 Sayılı Kanunlardaki Teşvik Oranları</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="kt_amcharts_1" class="card-img-top embed-responsive-item" style="height:500px;"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row mt-0 mt-lg-8">

                <div class="col-lg-12">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-lg-12">
                            <div class="card card-custom card-border" id="miktar">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            {{ $all ? $company->name : $sgk_company->name }} Toplam SGK Pirim Dağılımı
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="kt_amcharts_2" class="card-img-top embed-responsive-item" style="height:500px;"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin::Card-->

                    <!--end::Card-->
                </div>
                <div class="col-lg-12">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-lg-12">
                            <div class="card card-custom card-border">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            {{ $all ? $company->name : $sgk_company->name }} Toplam SGK Pirim Dağılım Tablosu
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered mb-6">
                                        <thead>
                                        <tr class="table-dark">
                                            <th scope="col">Kanun</th>
                                            <th scope="col">Tutar</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr>
                                            <th scope="row">5510 Sayılı Kanun</th>
                                            <td>{!! number_format($chart_pie_toplam_5510, 2, ',', '.') !!} TL</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">6111 Sayılı Kanun</th>
                                            <td>{!! number_format($chart_pie_toplam_6111, 2, ',', '.') !!} TL</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">27103 Sayılı Kanun</th>
                                            <td>{!! number_format($chart_pie_toplam_27103, 2, ',', '.') !!} TL</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">14857 Sayılı Kanun</th>
                                            <td>{!! number_format($chart_pie_toplam_14857, 2, ',', '.') !!} TL</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">7252 Sayılı Kanun</th>
                                            <td>{!! number_format($chart_pie_toplam_7252, 2, ',', '.') !!} TL</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Kalan Pirim Tutarı</th>
                                            <td>{!! number_format($chart_pie_toplam_tutar - ($chart_pie_toplam_5510 + $chart_pie_toplam_6111 + $chart_pie_toplam_27103 + $chart_pie_toplam_14857), 2, ',', '.') !!} TL</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Toplam Pirim Tutarı</th>
                                            <td>{!! number_format($chart_pie_toplam_tutar, 2, ',', '.') !!} TL</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">

                    <div class="card card-custom gutter-b ">
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">{{ $all ? $company->name : $sgk_company->name }} Ay Bazında Teşvik Hakedişleri</span>

                                </h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-icon btn-light-success mr-2">
                                    &nbsp;&nbsp;6111&nbsp;&nbsp;
                                </a>
                                <a href="#" class="btn btn-icon btn-warning">&nbsp;&nbsp;17103&nbsp;&nbsp;
                                </a>
                                <a href="#" class="btn btn-icon btn-info">&nbsp;&nbsp;7252&nbsp;&nbsp;
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="kt_amcharts_3" class="card-img-top embed-responsive-item" style="height:500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Base Table Widget 1-->
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">{{ $all ? $company->name : $sgk_company->name }} Aylara Göre Teşvik Tablo Gösterimi</span>
                                <span class="text-muted mt-3 font-weight-bold font-size-sm">6111 ve 17103 Sayılı Kanunlardan Kazanılan Teşvik Miktarları</span>
                            </h3>

                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-2 pb-0">
                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table table-borderless table-vertical-center">
                                    <thead>
                                    <tr>
                                        <th class="p-0 w-200px text-center text-dark-75 font-weight-bolder font-size-lg">Ay</th>
                                        <th class="p-0 min-w-100px text-center text-dark-75 font-weight-bolder font-size-lg">6111 Sayılı Kanun</th>
                                        <th class="p-0 min-w-100px text-center text-dark-75 font-weight-bolder font-size-lg">17103 Sayılı Kanun</th>
                                        <th class="p-0 min-w-100px text-center text-dark-75 font-weight-bolder font-size-lg">Toplam Tutar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data_gain as $key => $value)
                                    <tr>
                                        <td class="pl-0">
                                            <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">{{ $value['tarih'] }}</a>
                                            <span class="text-muted font-weight-bold d-block">Teşvik Tutarı</span>
                                        </td>

                                        @php $toplam_6111 = $value['6111']; @endphp

                                        @php $toplam_27103 = $value['27103']; @endphp

                                        @php
                                            $toplam = $toplam_6111 + $toplam_27103;
                                            if ($toplam == 0) {
                                               $yuzde_6111 = 0;
                                               $yuzde_27103 = 0;
                                            } else {
                                               $yuzde_6111 = ($toplam_6111 * 100 ) / $toplam;
                                               $yuzde_27103 = ($toplam_27103 * 100 ) / $toplam;
                                            }
                                        @endphp
                                        <td>
                                            <div class="d-flex flex-column w-100 mr-2">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-muted mr-2 font-size-sm font-weight-bold">
                                                        {{ intval($yuzde_6111) }}%
                                                    </span>
                                                    <span class="text-muted font-size-sm font-weight-bold">
                                                    {!! number_format($toplam_6111, 2, ',', '.') !!} TL
                                                    </span>
                                                </div>
                                                <div class="progress progress-xs w-100">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ intval($yuzde_6111) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column w-100 mr-2">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="text-muted mr-2 font-size-sm font-weight-bold">
                                                        {{ intval($yuzde_27103) }}%
                                                    </span>
                                                    <span class="text-muted font-size-sm font-weight-bold">
                                                    {!! number_format($toplam_27103, 2, ',', '.') !!} TL
                                                    </span>
                                                </div>
                                                <div class="progress progress-xs w-100">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ intval($yuzde_27103) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-left">
                                            <span class="text-dark-75 font-weight-bolder font-size-lg">
                                                {!! number_format($toplam_27103 + $toplam_6111, 2, ',', '.') !!} TL
                                            </span>
                                        </td>
                                    </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Base Table Widget 1-->
                </div>
            </div>

        </div>
    </div>
    @else
        <div class="d-flex flex-column-fluid">
            <div class=" container-fluid ">
                <div class="row mt-0 mt-lg-8">
                    <div class="col-xl-12">
                        <!--begin::Base Table Widget 1-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark"></span>
                                    <span class="text-muted mt-3 font-weight-bold font-size-sm">6111 Sayılı Kanundan Kazanılan Teşvik Müktarları</span>
                                </h3>

                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-2 pb-0">
                                Kayıt bulunamamıştır
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@push('scripts')
    <script>
        "use strict";

        var chart3 = AmCharts.makeChart("kt_amcharts_3", {
            "theme": "light",
            "type": "serial",
            "dataProvider": {!! $gain_array !!},
            "startDuration": 1,
            "graphs": [{
                "balloonText": "[[category]] ayındaki 6111 kazanımı: <b>[[value]] TL</b>",
                "fillAlphas": 0.9,
                "lineAlpha": 0.2,
                "title": "6111",
                "type": "column",
                "valueField": "6111"
            }, {
                "balloonText": "[[category]] ayındaki 27103 kazanımları: <b>[[value]] TL</b>",
                "fillAlphas": 0.9,
                "lineAlpha": 0.2,
                "title": "17103",
                "type": "column",
                "valueField": "27103"
            }, {
                "balloonText": "[[category]] ayındaki 7252 kazanımları: <b>[[value]] TL</b>",
                "fillAlphas": 0.9,
                "lineAlpha": 0.2,
                "title": "7252",
                "type": "column",
                "valueField": "7252"
            }],

            "plotAreaFillAlphas": 0.1,
            "depth3D": 60,
            "angle": 30,
            "categoryField": "tarih",
            "categoryAxis": {
                "gridPosition": "start"
            },
            "export": {
                "enabled": true
            }
        });

        var chart1 = AmCharts.makeChart("kt_amcharts_1", {
            "type": "pie",
            "theme": "light",
            "dataProvider": [{
                "country": "6111 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_6111 }}
            }, {
                "country": "27103 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_27103 }}
            }, {
                "country": "14857 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_14857 }}
            }, {
                "country": "7252 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_7252 }}
            }],
            "valueField": "litres",
            "titleField": "country",
            "balloon": {
                "fixedPosition": true
            },
            "export": {
                "enabled": true
            }
        });

        var chart2 = AmCharts.makeChart("kt_amcharts_2", {
            "type": "pie",
            "theme": "light",
            "dataProvider": [{
                "country": "6111 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_6111 }}
            }, {
                "country": "27103 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_27103 }}
            }, {
                "country": "7252 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_7252 }}
            }, {
                "country": "14857 Sayılı Kanun",
                "litres": {{ $chart_pie_toplam_14857 }}
            }, {
                "country": "Pirim İndirimi",
                "litres": {{ $chart_pie_toplam_5510 }}
            }, {
                "country": "Kalan",
                "litres": {{ $chart_pie_toplam_tutar - ($chart_pie_toplam_6111 + $chart_pie_toplam_27103 + $chart_pie_toplam_14857 + $chart_pie_toplam_5510 + $chart_pie_toplam_7252) }}
            }],
            "valueField": "litres",
            "titleField": "country",
            "balloon": {
                "fixedPosition": true
            },
            "export": {
                "enabled": true
            }
        });
        function exportChart() {


            chart1["export"].capture({}, function () {
                this.toPNG({}, function (base64) {
                    var data = {
                        _token: "{{csrf_token()}}",
                        base64: base64,
                        type: 1
                    };
                    $.ajax({
                        url: '/save-gain-image',
                        type: 'POST',
                        data: data,
                        success: function (data) {
                        },
                        error: function (r, s, e) {
                        }
                    });

                });
            });
            chart2["export"].capture({}, function () {
                this.toPNG({}, function (base64) {
                    var data = {
                        _token: "{{csrf_token()}}",
                        base64: base64,
                        type: 2
                    };
                    $.ajax({
                        url: '/save-gain-image',
                        type: 'POST',
                        data: data,
                        success: function (data) {
                        },
                        error: function (r, s, e) {
                        }
                    });

                });
            });
            chart3["export"].capture({}, function () {
                this.toPNG({}, function (base64) {
                    var data = {
                        _token: "{{csrf_token()}}",
                        base64: base64,
                        type: 3
                    };
                    $.ajax({
                        url: '/save-gain-image',
                        type: 'POST',
                        data: data,
                        success: function (data) {
                        },
                        error: function (r, s, e) {
                        }
                    });

                });
            });
        }
    </script>


    <script>
        "use strict";

        // Shared Colors Definition
        const primary = '#8950fc';
        const success = '#424a52';
        const info = '#8950FC';
        const warning = '#FFA800';
        const danger = '#e74230';

        $('#exportPdfHref').on('click', function(e) {
            e.preventDefault();
            exportChart();
            window.open(
                $(this).data('url'),
                '_blank'
            );
            //location.href = $(this).data('url');
        });
    </script>
@endpush

