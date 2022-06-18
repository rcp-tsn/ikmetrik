<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
            padding: 5px 10px 10px 10px;
            height: 100% !important;


        }
        .headertbl {
            padding-top:10px;
            border: solid #ccc 1px;
        }

        p {
            line-height: 30px;
        }
        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            width: 100%;
            height: 80px;


        }

        body td
        {
            font-family: DejaVu Sans, sans-serif;
            font-weight: bold;
        }
        .rotate90 {
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            -ms-transform: rotate(270deg);
            transform: rotate(270deg);
        }
    </style>

</head>
<body>
<table style="margin-right: auto;margin-left:auto; margin-top: 5px" id="teklif_performans" class="table">
    <tbody>
    <tr>
        <td > <img style="border:none; height: 60px; width: 60px; margin-top: 10px;" src="{{$qrcode1}}">
            <br>
            <img  style="border:none; height: 70px;width: 100px; margin-right: 5px; "  src="{{public_path() . '/assets/media/zamane/zamane_logo.png'}}">
        </td>
        <td><p style="font-size: 9px">Vergi No:{{$sgk_company->tax_number}}<br>{{$company->name}}<br>{{$date}}<br>{{$metin}}</p></td>

        <td > <img style="border:none; height: 60px;width: 60px; margin-top: 10px;" src="{{$qrcode2}}">
            <br>
            <img  style="border:none; height: 70px;width: 100px; margin-right: 5px; "   src="{{public_path() . '/assets/media/zamane/zamane_logo.png'}}">
        </td>
        <td><p style="font-size: 9px">{{$working->first_name}} {{$working->last_name}}<br>{{$working->employee_personel->identity_number}}<br>{{$date2}}<br>{{$metin}}</p></td>
    </tr>
    <tr></tr>
    </tbody>
</table>



<table style="margin-right: auto;margin-left:auto; margin-top: 5px" id="teklif_performans" class="table">
    <tbody>
    <tr>
        <td><img style="border:none; margin-top: 10px; height: 25px;width: 25px; margin-right: 5px; "   src="{{public_path() . '/assets/media/zamane/tick.png'}}">
        </td>
        <td><p style="font-size: 10px;font-weight: bold;margin-top: 5px;">{{$working->employee_personel->identity_number}} TC KİMLİK NUMARALI {{$working->first_name}} {{$working->last_name}} ADLI PERSONEL İÇİN 3D SECURİTY KİMLİK DOĞRULAMA BAŞARIYLA YAPILMIŞTIR</p></td>
    </tr>
    <tr></tr>
    </tbody>
</table>
</body>
</html>
