<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!--
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
-->
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
        .page_break {
            page-break-after: avoid;
        }
    </style>
</head>
<body>
<table class="headertbl">
    <tbody>
    <tr >
        <td style="width:3%;height:10px"><img  style="border:none;height: 80px;width: 120px;" src="{{public_path().'/'.$company->logo}}" /></td>

        <td style="vertical-align:middle">
            <h3 align="center">{{ $company->name }}</h3>
        </td>

    </tr>
    </tbody>
</table>
<table class="table table-bordered" style="font-size: 10px">
    <tbody>
    <tr>
        <td colspan="9" style="text-align: center"><strong>DİSİPLİN TUTANAĞI</strong></td>
        <td><strong>Tutanak No: 0000{{$discipline->id}}</strong></td>
    </tr>
    <tr>
        <td colspan="10"><strong>DİSİPLİN CEZASI VERİLMEK İSTENEN PERSONELİN</strong></td>
    </tr>
    <tr>
        <td colspan="10" style="height: 40px"><strong>ADI SOYADI: {{$employee->first_name}} {{$employee->last_name}} </strong></td>
    </tr>
    <tr>
        <td colspan="10" style="height: 40px"><strong>OLAY YERİ: {{$request->map}} </strong></td>
    </tr>
    <tr>
        <td colspan="10" style="height: 40px"><strong>OLAY TARİHİ: {{date('d/m/Y',strtotime($request->date))}} </strong></td>
    </tr>

    <tr>
        <td colspan="10"><strong>OLAYIN TANIMI (yer,saat,olay, varsa görgü tanıkları): </strong></td>
    </tr>
    <tr height="5px"><td colspan="11">
        {{$request->definition}}
        </td></tr>
    <tr>
        <td colspan="10"><strong>İŞLENEN SUÇLAR</strong></td>
    </tr>

    <tr>
        <td colspan="11">
           <ul>
               @foreach ($disciplines as $key => $cipline)
               <li>  {{$cipline}}</li>
               @endforeach
           </ul>
        </td>
    </tr>

    <tr>
        <td colspan="10"><strong>Fiili İşleyen Personel Savunması</strong></td>
    </tr>
    <tr><td colspan="11"><hr color="#000000"></td></tr>

    <tr>
        <td colspan="10"><strong>Şahit {{$sahit1->full_name}} Ad Soyad / {{$request->accept_type == 1 ? 'İMZA' : 'ZAMAN DAMGASI'}}</strong></td>
    </tr>
    <tr><td colspan="11"><hr color="#000000"></td></tr>

    <tr>
        <td colspan="10"><strong>Şahit {{$sahit2->full_name}} Ad Soyad / {{$request->accept_type == 1 ? 'İMZA' : 'ZAMAN DAMGASI'}}</strong></td>
    </tr>
    <tr><td colspan="11"><hr color="#000000"></td></tr>

    <tr>
        <td colspan="10"><strong>ÖNERİLEN CEZA</strong></td>
    </tr>
    <tr>
        <td colspan="10" style="height: 40px">{{config('variables.ceza_type')[$request->proposed]}}</td>
    </tr>

    <tr>
        <td colspan="10">Açıklama: </td>
    </tr>
    <tr>
        <td colspan="10" style="height: 30px"></td>
    </tr>
    <tr>
        <td colspan="10">Fiili gerçekleştiren kişinin üst yönetici Ad Soyad / İmza</td>
    </tr>
    <tr height="5px"><td colspan="11">{{$employee->employee_ust($employee->id,true)}}</td></tr>
    <tr>
        <td colspan="10"><strong>KARAR / İK DEPARTMANI</strong></td>
    </tr>
    <tr>
        <td colspan="10" style="height: 30px"></td>
    </tr>
    <tr>
        <td colspan="3">Ad Soyad / {{$request->accept_type == 1 ? 'İMZA' : 'ZAMAN DAMGASI'}}</td>
        <td colspan="3">Ad Soyad / {{$request->accept_type == 1 ? 'İMZA' : 'ZAMAN DAMGASI'}}</td>
        <td colspan="4">Ad Soyad / {{$request->accept_type == 1 ? 'İMZA' : 'ZAMAN DAMGASI'}}</td>

    </tr>
    <tr height="5px"><td colspan="11"><hr color="#000000"></td></tr>
    </tbody>
</table>
<br>
<br>

<div class="page_break"></div>
<br>
<br>
<br>
<table class="headertbl">
    <tbody>
    <tr >
        <td style="vertical-align:middle;width:3%;height:10px">
            <h3 align="center">OLAY GÖRÜNTÜLERİ</h3>
        </td>

    </tr>
    </tbody>
</table>
    <table>
        <tbody>

        <tr>
            @foreach($tutanakEk as $file)
            <td><img  style="border:none;height: 250px;width: 300px;" src="{{public_path().'/'.$file->file}}" /></td>
            @endforeach
        </tr>
        </tbody>
    </table>

</body>
</html>
