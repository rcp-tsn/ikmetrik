<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <!--
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
-->
    <style type="text/css" media="screen">

        body {


            width: 100% !important;
            font-family: "times-new-roman";
            font-style:normal;
            font-size:10pt;
            color: #000;
            padding: 10px;
            height: 100% !important;


        }

        p {
            line-height: 30px;
        }
    </style>
    <link href="{{public_path()}}/assets/plugins/global/plugins_pdf.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="{{public_path()}}/assets/css/style_pdf.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />

    @php $toplam_6111 = 0; @endphp
    @php $toplam_27103 = 0; @endphp
    @php $toplam_7252 = 0; @endphp
    @php $toplam_5510 = 0; @endphp
    @php $toplam_14857 = 0; @endphp
    @php $toplam_tutar = 0; @endphp

    @foreach($active_gains as $active_gain)
        @php
            $toplam_6111 += $active_gain->law_6111;
            $toplam_7252 += $active_gain->law_7252;
            $toplam_5510 += $active_gain->law_5510;
            $toplam_27103 += $active_gain->law_27103;
            $toplam_14857 += $active_gain->law_14857;
            $toplam_tutar += $active_gain->total_amount;
        @endphp
    @endforeach


    @php
        $toplam = $toplam_6111 + $toplam_27103 + $toplam_14857 + $toplam_7252;
        if ($toplam == 0) {
           $yuzde_6111 = 0;
           $yuzde_27103 = 0;
           $yuzde_14857 = 0;
           $yuzde_7252 = 0;
        } else {
           $yuzde_6111 = ($toplam_6111 * 100 ) / $toplam;
           $yuzde_7252 = ($toplam_7252 * 100 ) / $toplam;
           $yuzde_27103 = ($toplam_27103 * 100 ) / $toplam;
           $yuzde_14857 = ($toplam_14857 * 100 ) / $toplam;
        }
        $chart_pie_toplam_6111 = $toplam_6111;
        $chart_pie_toplam_7252 = $toplam_7252;
        $chart_pie_toplam_5510 = $toplam_5510;
        $chart_pie_toplam_27103 = $toplam_27103;
        $chart_pie_toplam_14857 = $toplam_14857;
        $chart_pie_toplam_tutar = $toplam_tutar;
    @endphp

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
        function init() {
            google.load('visualization', '44', { packages: ['corechart', 'controls'] });
            var interval = setInterval(function() {
                if ( google.visualization !== undefined && google.visualization.DataTable !== undefined && google.visualization.PieChart !== undefined ){ clearInterval(interval);
                    window.status = 'ready';
                    drawChart();
                }
            }, 100);
        }



        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['6111',     {{ $chart_pie_toplam_6111 }}],
                ['27103',      {{ $chart_pie_toplam_27103 }}],
                ['7252',      {{ $chart_pie_toplam_7252 }}],
                ['14857',  {{ $chart_pie_toplam_14857 }}]
            ]);

            var options = {
                //pieHole: 0.4,
                is3D: true,
                width: '100%',
                height: '100%',
                pieSliceText: 'percentage',
                pieSliceTextStyle: {
                    fontSize:'17',
                    color: 'white',
                },
                colors: ['#0598d8', '#f97263', '#424a52', '#126e3f'],
                chartArea: {
                    left: "3%",
                    top: "3%",
                    height: "100%",
                    width: "100%"
                }

            };


            var data2 = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['6111',     {{ $chart_pie_toplam_6111 }}],
                ['27103',      {{ $chart_pie_toplam_27103 }}],
                ['14857',  {{ $chart_pie_toplam_14857 }}],
                ['7252',  {{ $chart_pie_toplam_7252 }}],
                ['Pirim İndirimi',  {{ $chart_pie_toplam_5510 }}],
                ['Kalan',  {{ abs($chart_pie_toplam_tutar - ($chart_pie_toplam_6111 + $chart_pie_toplam_27103 + $chart_pie_toplam_14857 + $chart_pie_toplam_5510 + $chart_pie_toplam_7252 )) }}]
            ]);

            var options2 = {
                is3D: true,
                width: '100%',
                height: '100%',
                pieSliceText: 'percentage',
                pieSliceTextStyle: {
                    fontSize:'17',
                    color: 'white',
                },
                colors: ['#0598d8', '#f97263', '#f1e919', '#3ead24', '#424a52', '#126e3f'],
                chartArea: {
                    left: "3%",
                    top: "3%",
                    height: "100%",
                    width: "100%"
                }
            };


            var data3 = new google.visualization.arrayToDataTable([
                {!! $data_for_pdf_array !!}
            ]);

            var options3 = {
                hAxis: {title: 'AYLAR',  titleTextStyle: {color: '#e74230'}},
                vAxis: {minValue: 0}
            };


            var data3 = google.visualization.arrayToDataTable([
                {!! $data_for_pdf_array !!}
            ]);




            var chart = new google.visualization.PieChart(document.getElementById('chart_1B'));
            var chart2 = new google.visualization.PieChart(document.getElementById('chart_2A'));
            var chart3 = new google.visualization.AreaChart(document.getElementById('chart_3A'));

            chart.draw(data, options);
            chart2.draw(data2, options2);
            chart3.draw(data3, options3);

        }
    </script>

</head>
<body onload="init()">

<div class="page">
    <br/>
    <div class="row mt-0 mt-lg-8">
        <div class="col-xl-12">
            <div class="container-fluid d-flex flex-column flex-md-row align-items-lg-start justify-content-between">
                <div class="nav nav-dark font-size-h5-md text-dark-75">
                    <strong>{{ $all ? $company->name : $sgk_company->name }}</strong>
                </div>
                <div class="nav nav-dark font-size-h5-md">
                    Rapor Tarihi: <em>{{ \Carbon\Carbon::now(config('app.tz'))->format('d/m/Y') }}</em><br/><br/>
                </div>
            </div>
        </div>
    </div>


    <p class="text-justify font-size-h3" >
        &nbsp; &nbsp; &nbsp;More Payroll Bordrolama ve Teşvik Hizmetleri kurulduğu günden bu yana yaklaşık 13 yıllık sektörel deneyimi ve Bursa, İstanbul, Antalya ofisleri ile 42 ilde siz değerli paydaşlarına hizmet vermektedir.<br/>
        Şirketinizin <strong>{{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</strong>  yılı SGK Teşvik raporu maksimum tasarruf ve sıfır risk anlayışı ile aşağıda bilgilerinize sunulmuştur..</p>
    <p class="text-justify font-size-h3">&nbsp; &nbsp; &nbsp;More Payroll Teşvik Birimi<br/><br/></p>
</div>
<div >
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-6">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-xl-12">
                            <!--begin::Base Table Widget 1-->
                            <div class="card card-custom card-border">
                                <!--begin::Header-->
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <span class="card-label">Rapor 1A: <span class="font-weight-bold text-danger">{{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</span> 6111-17103-7252 Sayılı Kanunlardaki Teşvik Miktarları</span>
                                    </h4>
                                </div>
                                <div class="card-body pt-2 pb-0">
                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-vertical-center">
                                            <tbody>
                                            <tr>
                                                <td class="pl-0 py-5">
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
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">6111</a>
                                                    <span class="font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                @php $toplam_6111 = 0; @endphp
                                                @php $toplam_27103 = 0; @endphp
                                                @php $toplam_7252 = 0; @endphp
                                                @php $toplam_5510 = 0; @endphp
                                                @php $toplam_14857 = 0; @endphp
                                                @php $toplam_tutar = 0; @endphp

                                                @foreach($active_gains as $active_gain)
                                                    @php
                                                        $toplam_6111 += $active_gain->law_6111;
                                                        $toplam_5510 += $active_gain->law_5510;
                                                        $toplam_7252 += $active_gain->law_7252;
                                                        $toplam_27103 += $active_gain->law_27103;
                                                        $toplam_14857 += $active_gain->law_14857;
                                                        $toplam_tutar += $active_gain->total_amount;
                                                    @endphp
                                                @endforeach


                                                @php
                                                    $toplam = $toplam_6111 + $toplam_27103 + $toplam_14857 + $toplam_7252;
                                                    if ($toplam == 0) {
                                                       $yuzde_6111 = 0;
                                                       $yuzde_7252 = 0;
                                                       $yuzde_27103 = 0;
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
                                                    <span class="mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_6111) }}%
                                                    </span>
                                                            <span class="font-size-md font-weight-bold">
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
                                                <td class="pl-0 py-5">
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
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">27103</a>
                                                    <span class="font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                <td>

                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_27103) }}%
                                                    </span>
                                                            <span class="font-size-md font-weight-bold">
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
                                                <td class="pl-0 py-5">
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
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">14857</a>
                                                    <span class="font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                <td>

                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_14857) }}%
                                                    </span>
                                                            <span class="font-size-md font-weight-bold">
                                                        {!! number_format($toplam_14857, 2, ',', '.') !!} TL
                                                    </span>
                                                        </div>
                                                        <div class="progress progress-xs w-100">
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ intval($yuzde_14857) }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="pl-0 py-5">
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
                                                    <a href="#" class="text-dark font-weight-bolder text-hover-primary mb-1 font-size-lg">7252</a>
                                                    <span class="font-weight-bold d-block">Teşvik Tutarı</span>
                                                </td>
                                                <td>

                                                    <div class="d-flex flex-column w-100 mr-2">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="mr-2 font-size-md font-weight-bold">
                                                        {{ intval($yuzde_7252) }}%
                                                    </span>
                                                            <span class="font-size-md font-weight-bold">
                                                        {!! number_format($toplam_7252, 2, ',', '.') !!} TL
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
                <div class="col-xl-6">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-xl-12">
                            <div class="card card-custom card-border">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h4 class="card-title">
                                            <span class="card-label">Rapor 1B: <span class="font-weight-bold text-danger">{{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</span> 6111, 17103 7252 Sayılı Kanunlardaki Teşvik Oranları</span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="chart_1B" style="width: 375px; height: 290px;"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row mt-0 mt-lg-8">

                <div class="col-lg-6">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-lg-12">
                            <div class="card card-custom card-border" id="miktar">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            Rapor 2A: <span class="font-weight-bold text-danger">{{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</span> Toplam SGK Pirim Dağılımı
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="chart_2A" style="width: 375px; height: 265px;"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin::Card-->

                    <!--end::Card-->
                </div>
                <div class="col-lg-6">
                    <div class="row mt-0 mt-lg-8">
                        <div class="col-lg-12">
                            <div class="card card-custom card-border">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">
                                            Rapor 2B: <span class="font-weight-bold text-danger">{{ getFullMonthName($gain_incentive_first->accrual->format('M')) }} / {{ date("Y") }}</span> Toplam SGK Pirim Dağılım Tablosu
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body" style="padding: 1rem;">
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

            <div style="page-break-after: always;"></div>
            <br/>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-custom card-border" id="miktar">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Rapor 3A: <span class="font-weight-bold text-danger">{{ $all ? $company->name : $sgk_company->name }}</span> Ay Bazında Genel Teşvik Hakedişleri
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart_3A" style="width: 700px; height: 300px;"></div>
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
                                <span class="card-label font-weight-bolder text-dark">Rapor 3B: <span class="font-weight-bold text-danger">{{ $all ? $company->name : $sgk_company->name }}</span> Aylara Göre Teşvik Tablo Gösterimi</span>
                            </h3>

                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-1 pb-0">
                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table table-bordered mb-2">
                                    <thead>
                                    <tr class="table-dark">
                                        <th scope="col">Ay/Yıl</th>
                                        <th scope="col">6111 S. Kanun</th>
                                        <th scope="col">17103 S. Kanun</th>
                                        <th scope="col">7252 S. Kanun</th>
                                        <th scope="col">Toplam Tutar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $alt_toplam_6111 = 0;
                                        $alt_toplam_27103 = 0;
                                        $alt_toplam_7252 = 0;
                                        $alt_toplam = 0;
                                    @endphp
                                    @foreach($data_gain as $key => $value)
                                        @php $toplam_6111 = $value['6111']; @endphp

                                        @php $toplam_7252 = $value['7252']; @endphp
                                        @php $toplam_27103 = $value['27103']; @endphp

                                        @php
                                            $toplam = $toplam_6111 + $toplam_27103 + $toplam_7252;
                                            if ($toplam == 0) {
                                               $yuzde_6111 = 0;
                                               $yuzde_27103 = 0;
                                               $yuzde_7252 = 0;
                                            } else {
                                               $yuzde_6111 = ($toplam_6111 * 100 ) / $toplam;
                                               $yuzde_7252 = ($toplam_7252 * 100 ) / $toplam;
                                               $yuzde_27103 = ($toplam_27103 * 100 ) / $toplam;
                                            }
                                        @endphp

                                        @php
                                            $alt_toplam_6111 += $toplam_6111;
                                            $alt_toplam_27103 += $toplam_27103;
                                            $alt_toplam_7252 += $toplam_7252;
                                            $alt_toplam += $toplam;
                                        @endphp
                                    <tr>
                                        <th scope="row">{{ $value['tarih'] }}</th>
                                        <td>{!! number_format($toplam_6111, 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($toplam_27103, 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($toplam_7252, 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($toplam_27103 + $toplam_6111 + $toplam_7252, 2, ',', '.') !!} TL</td>
                                    </tr>
                                    @endforeach
                                    <tr class="table-ikmetrik">
                                        <th scope="row">TOPLAM</th>
                                        <td>{!! number_format($alt_toplam_6111, 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($alt_toplam_27103, 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($alt_toplam_7252, 2, ',', '.') !!} TL</td>
                                        <td>{!! number_format($alt_toplam, 2, ',', '.') !!} TL</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br/>
                            </div>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Base Table Widget 1-->
                </div>
            </div>
            <div style="page-break-after: always;"></div>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Base Table Widget 1-->
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-1">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Rapor 4A: <span class="font-weight-bold text-danger">{{ $all ? $company->name : $sgk_company->name }}</span> Faturalandırma</span>
                            </h3>

                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-1 pb-0">
                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table table-bordered mb-2">
                                    <thead>
                                    <tr class="table-dark">
                                        <th scope="col">Ay/Yıl</th>
                                        <th scope="col">6111 S. Kanun</th>
                                        <th scope="col">17103 S. Kanun</th>
                                        <th scope="col">7252 S. Kanun</th>
                                        <th scope="col">Toplam Tutar</th>
                                        <th scope="col">Hak Ediş Oranı</th>
                                        <th scope="col">More Payroll<br/>Hakediş</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $alt_toplam_6111 = 0;
                                        $alt_toplam_27103 = 0;
                                        $alt_toplam_7252 = 0;
                                        $alt_toplam = 0;
                                        $last_month = 1;
                                    @endphp
                                    @foreach($data_gain2 as $key => $value)
                                        @if($last_month == 1)
                                            <?php $date = $value['tarih']; ?>
                                            @php $toplam_6111 = $value['6111']; @endphp
                                            @php $toplam_7252 = $value['7252']; @endphp


                                            @php $toplam_27103 = $value['27103']; @endphp

                                            @php
                                                $toplam = $toplam_6111 + $toplam_27103;
                                                if ($toplam == 0) {
                                                   $yuzde_6111 = 0;
                                                   $yuzde_7252 = 0;
                                                   $yuzde_27103 = 0;
                                                } else {
                                                   $yuzde_6111 = ($toplam_6111 * 100 ) / $toplam;
                                                   $yuzde_7252 = ($toplam_7252 * 100 ) / $toplam;
                                                   $yuzde_27103 = ($toplam_27103 * 100 ) / $toplam;
                                                }
                                            @endphp

                                            @php
                                                $alt_toplam_6111 += $toplam_6111;
                                                $alt_toplam_27103 += $toplam_27103;
                                                $alt_toplam_7252 += $toplam_7252;
                                                $alt_toplam += $toplam;
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $value['tarih'] }}</th>
                                                <td>{!! number_format($toplam_6111, 2, ',', '.') !!} TL</td>
                                                <td>{!! number_format($toplam_27103, 2, ',', '.') !!} TL</td>
                                                <td>{!! number_format($toplam_7252, 2, ',', '.') !!} TL</td>
                                                <td>{!! number_format($toplam_27103 + $toplam_6111 + $toplam_7252, 2, ',', '.') !!} TL</td>
                                                <td> % {!! number_format($sgk_company->company_gain_ratio, 2, ',', '.') !!}</td>
                                                <td>{!! number_format((($toplam_7252 + $toplam_27103 + $toplam_6111) * $sgk_company->company_gain_ratio) / 100 , 2, ',', '.') !!} TL</td>
                                            </tr>
                                        @endif
                                        @php $last_month++; @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                <br/>


                            </div>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Base Table Widget 1-->
                </div>
            </div>







            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Base Table Widget 1-->
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-1">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Rapor 4B: <span class="font-weight-bold text-danger">{{ $all ? $company->name : $sgk_company->name }} <?php echo ' '; ?></span>  27256 HAKEDİŞ RAPORU </span>
                            </h3>

                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body pt-1 pb-0">
                            <!--begin::Table-->
                            <div class="table-responsive">
                                <table class="table table-bordered mb-2">
                                    <thead>
                                    <tr class="table-dark">
                                        <th scope="col">Ay/Yıl</th>
                                        <th scope="col">27256 S. Kanun Gün Sayısı</th>
                                        <th>Birim Miktarı</th>
                                        <th scope="col">Hak Ediş Oranı</th>
                                        <th scope="col">27256 S. Kanun Hakediş</th>
                                        <th scope="col">More Payroll<br/>Hakediş</th>
                                    </tr>
                                    </thead>


                                    <tbody>
                                    @if(!empty($sgk_company2))
                                    @foreach($sgk_company2 as $company)

                                        @php

                                            $tarih[] = array('01'=>'Ocak','02'=>'Şubat','03'=>'Mart','04'=>'Nisan','05'=>'Mayıs','06'=>'Haziran','07'=>'Temmuz','08'=>'Ağustos','09'=>'Eylül','10'=>'Ekim','11'=>'Kasım','12'=>'Aralık');
                                            $a = explode('-',$company['accrual']);
                                            foreach ($gains as $gain)
                                            {
                                                if ($a[0]==$gain[0])
                                                {
                                                    $deger = $gain[1];
                                                }
                                            }
                                            $total_gain = $company['total_day'] * $deger;
                                            $morepayroll_gain = ($total_gain * $sgk_company->company_gain_ratio ) / 100;
                                        @endphp
                                            <tr>
                                                <th scope="row">{{ $tarih[0][$a[1]] }}</th>
                                                <td>{!! $company['total_day'] !!} Gün</td>
                                                <td>{!! number_format($deger,2,',','.')  !!} TL</td>
                                                <td> % {!! number_format($sgk_company->company_gain_ratio, 2, ',', '.') !!}</td>
                                                <td>{!! number_format($total_gain, 2, ',', '.') !!} TL</td>
                                                <td>{!! number_format($morepayroll_gain , 2, ',', '.') !!} TL</td>
                                            </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <br/>


                            </div>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--end::Base Table Widget 1-->
                </div>
            </div>











        </div>
    </div>
</div>


</body>
</html>
