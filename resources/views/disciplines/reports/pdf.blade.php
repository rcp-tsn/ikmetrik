<html>
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <link href="{{public_path()}}/assets/plugins/global/plugins_pdf.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <link href="{{public_path()}}/assets/css/style_pdf.bundle.css?v=7.0.3" rel="stylesheet" type="text/css" />
    <script type="text/javascript">

        function init() {
            google.load('visualization', '44', { packages: ['corechart', 'controls'] });
            var interval = setInterval(function() {
                if ( google.visualization !== undefined && google.visualization.DataTable !== undefined && google.visualization.ComboChart !== undefined ){ clearInterval(interval);
                    window.status = 'ready';
                    drawChart();
                }
            }, 100);
        }
        function drawChart() {

            // Some raw data (not necessarily accurate)
            var data = google.visualization.arrayToDataTable([
                ['AYLAR', 'DİSİPLİN SAYISI' ],
                ['OCAK',  {{isset($infos['01']) ? $infos['01'] : 0 }}],
                ['ŞUBAT',  {{isset($infos['02']) ? $infos['02'] : 0 }}],
                ['MART',  {{isset($infos['03']) ? $infos['03'] : 0 }}],
                ['NİSAN',  {{isset($infos['04']) ? $infos['04'] : 0 }}],
                ['MAYIS',  {{isset($infos['05']) ? $infos['05'] : 0 }}],
                ['HAZIRAN',  {{isset($infos['06']) ? $infos['06'] : 0 }}],
                ['TEMMUZ',  {{isset($infos['07']) ? $infos['07'] : 0 }}],
                ['AĞUSTOS',  {{isset($infos['08']) ? $infos['08'] : 0 }}],
                ['EYLUL',  {{isset($infos['09']) ? $infos['09'] : 0 }}],
                ['EKIM',  {{isset($infos['10']) ? $infos['10'] : 0 }}],
                ['KASIM',  {{isset($infos['11']) ? $infos['11'] : 0 }}],
                ['ARALIK',  {{isset($infos['12']) ? $infos['12'] : 0 }}],

            ]);



            var options = {
                title : 'DISIPLIN RAPOR GRAFIGI',
                vAxis: {title: 'Cups'},
                hAxis: {title: 'AYLAR'},
                seriesType: 'bars',
                series: {5: {type: 'line'}},
                colors: ['#ff7b1d', '#f97263', '#424a52', '#126e3f'],



            };

            var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
            chart.draw(data, options);


        }
    </script>

    <style>
        .headertbl {
            padding-top:20px;
            border: solid #ccc 1px;
        }
        body {
            width: 100%;
            font-family: "times-new-roman";
            font-style:normal;
            font-size:12pt;
            color: #000;
        }
    </style>

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
</head>
<body onload="init()">

<table class="headertbl" >
    <tbody>
    <tr >
        <td style="width:5%;height:70px"><img style="border:none;" src="{{public_path().'/'.$company->logo}}" /></td>

        <td style="vertical-align:middle">
            <h3 align="center" style="font-size: 35px;font-weight: bold">{{ $company->name }}</h3>
        </td>

    </tr>
    </tbody>
</table>

<div class="headertbl" style="margin-top: 35px;">
    <div class="alert  alert-custom alert-warning" role="alert">
        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
        <div class="" style="font-size: 25px;font-weight: bold">{{$id == 0 ? 'ŞUANDA FİRMANIZIN TÜM PERSONEL DİSİPLİN SUÇ KAYITLARI RAPORLANMAKTADIR!!' : $employee->full_name.' VERİLERİ LİSTELENMEKTEDİR'}}</div>
    </div>
</div>
    <div class=" gutter-b mt-15">

        <label style="font-size: 20px;font-weight: bold">SEÇİLİ PERSONEL : {{$id == 0 ? 'TÜM PERSONELLER' : $employee->full_name}}</label>
        <br>
        <br>
        <label style="font-size: 20px;font-weight: bold">TOPLAM DİSİPLİN SAYISI : {{$disciplines->count()}}</label>
        <br>
    </div>


<div class="headertbl">
    <div id="chart_div" style="width: 1500px; height: 600px;"></div>
</div>

</body>
</html>






