<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!--
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
-->


    <style type="text/css" data-selector="modalContainer">
        #page {
            width: 842px !important;
        }

        table {
            width: 100%;
        }


        td.stil {
            height: 28px;
            width: 280px;
            padding-left: 5px;
            font-weight: bold;
        }

        .space-left {
            padding-left: 5px;
        }

        .demo-section {
            position: absolute;
            top: 11px;
            left: 805px;
        }

        td {
            width: 320px;
        }



        #wizard {
            width: 100%;
            position: relative;
            margin-top: 50px;
        }

        #wizard img {
            width: 100%;
        }

        #wizard .adim {
            position: absolute;
            width: 25%;
            color: #000 !important;
            font-family: verdana;
            font-size: 8px;
            font-weight: bold;
            top: 46%;
            z-index: 99;
        }

        #wizard .adim1 {
            left: 3%;
        }

        #wizard .adim2 {
            left: 28%;
        }

        #wizard .adim3 {
            left: 52%;
        }

        #wizard .adim4 {
            left: 77%;
        }

        #wizard .tarih {
            position: absolute;
            width: 25%;
            color: #000 !important;
            font-family: verdana;
            font-size: 8px;
            font-weight: bold;
            top: 31%;
            z-index: 199;
        }

        #wizard .tarih1 {
            left: 3%;
        }

        #wizard .tarih2 {
            left: 28%;
            top: 62%;
        }

        #wizard .tarih3 {
            left: 52%;
        }

        #wizard .tarih4 {
            left: 77%;
            top: 62%;
        }

        table#sub tr td {
            text-align: center;
            line-height: 20px;
            font-size: 15px;
        }

        table#headerTable {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <table id="headerTable" class="m-b-sm" border="1" cellpadding="0" cellspacing="0" data-selector="modalContainer">
                    <thead>
                    <tr>
                        <td> <img  style="border:none; height: 70px;width: 100px; margin-right: 5px; "  src="{{public_path().'/'.$company->logo}}"></td>
                        <td>
                            <table id="sub" class="m-b-sm" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td colspan="5" class="stil" style="text-align: center; border-bottom: 1px solid gray;font-size: 18px;line-height: 52px;"><b>İZİN TALEP FORMU</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5" rowspan="2" class="stil" style="border-bottom: 1px solid gray;border-right: 1px solid gray">
                                        {{isset($sgk_company->name) ? $sgk_company->name : ''}}
                                    </td>

                                </tr>


                                </tbody></table>
                        </td>
                    </tr>
                    </thead>
                </table>
                <table class="m-b-sm" border="1" cellpadding="0" cellspacing="0" data-selector="modalContainer">
                    <tbody><tr>
                        <td colspan="4" align="left" valign="middle" class="stil" style="background: #808080;"><strong>Personel Bilgileri</strong></td>
                    </tr>
                    <tr>
                        <td class="stil"> Kimlik No</td>
                        <td colspan="3" data-prop="Adress" class="space-left"> </td>
                    </tr>
                    <tr>
                        <td class="stil">Ad - Soyad </td>
                        <td colspan="3" data-prop="Adress" class="space-left">{{ $employeeLeave->employee->first_name }} {{ $employeeLeave->employee->last_name }}</td>
                    </tr>
                    <tr>
                        <td class="stil">Departman </td>
                        <td colspan="3" data-prop="Adress" class="space-left">{{$employeeLeave->department()}}</td>
                    </tr>
                    <tr>
                        <td class="stil">Pozisyon </td>
                        <td colspan="3" data-prop="Adress" class="space-left"></td>
                    </tr>
                    <tr>
                        <td class="stil">İşe Giriş Tarihi</td>
                        <td colspan="3" data-prop="Adress" class="space-left"> {{ date('d/m/Y', strtotime($employeeLeave->employee->job_start_date)) }}</td>
                    </tr>

                    <tr>
                        <td colspan="4" class="stil"></td>
                    </tr>


                    <tr>
                        <td colspan="4" align="left" valign="middle" class="stil" style="background: #80808038;"><strong>İzin Bilgileri</strong></td>
                    </tr>

                    <tr>
                        <td class="stil">İzin Türü</td>
                        <td colspan="3" data-prop="Adress" class="space-left">{{ config('variables.employees.leave_type')[$employeeLeave->leave_type] }}</td>
                    </tr>

                    <tr>
                        <td class="stil">İzin Başlangıç Tarihi</td>
                        <td colspan="3" class="space-left">{{ date('d/m/Y', strtotime($employeeLeave->start_date)) }}</td>
                    </tr>
                    <tr>
                        <td class="stil">İzin Bitiş Tarihi</td>
                        <td colspan="3">{{ date('d/m/Y', strtotime($employeeLeave->finish_date)) }}</td>

                    </tr>
                    <tr>
                        <td class="stil">İşe Başlama Tarihi</td>
                        <td colspan="3">{{ date('d/m/Y', strtotime($employeeLeave->job_start_date)) }}</td>

                    </tr>
                    <tr>
                        <td class="stil">İzin Süresi</td>
                        <td colspan="3" class="space-left">{{ $employeeLeave->days }} Gün </td>
                    </tr>
                    <tr>
                        <td class="stil">Açıklama</td>
                        <td colspan="3" class="space-left" style="height: 102px"> {{ $employeeLeave->description }}  </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="stilBuyuk" style="height: 90px; padding-top: -5px; text-align: center; padding-bottom: 45px;">
                            <font>
                                Yukarda bilgileri bulunan iznimi belirtilen tarihlerde kullanmak istiyorum.<br>
                                Ad-Soyad/Tarih/İmza
                            </font>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" class="stil"></td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;" align="center" class="stil">Birinci Yöneticisi</td>
                        <td style="font-weight: bold;" align="center" class="space-left">İkinci Yöneticisi</td>
                        <td style="font-weight: bold;" align="center" class="space-left">İnsan Kaynakları</td>
                    </tr>
                    <tr>
                        @for($i = 0;$i<=2;$i++)
                            <td class="space-left" align="center" height="30px">
                                @if(isset($leave[$i]))
                                    @if($leave[$i]->type_authority == $i+1)
                                        {{ $leave[$i]->user->name }}
                                    @endif
                                @else
                                    @if(isset($employeeManagers[$i+1]['name']))
                                        {{ $employeeManagers[$i+1]['name'] }}
                                    @else
                                        Yönetici Yoktur
                                    @endif

                                @endif
                            </td>
                        @endfor

                    </tr>
                    <tr>
                        <td class="space-left" align="center" height="30px"> @if(isset($leaves[0]))
                                @if($leaves[0]->type_authority == 1)
                                    @if($leaves[0]->status == 1)
                                        Onay Bekleniyor
                                    @elseif($leaves[0]->status == 2)
                                        Onaylandı
                                    @else
                                        Reddedildi
                                    @endif
                                @endif
                            @else
                                Onay Bekleniyor
                            @endif </td>
                        <td class="space-left" align="center" height="30px"> @if(isset($leaves[1]))
                                @if($leaves[1]->type_authority == 2)
                                    @if($leaves[1]->status == 1)
                                        Onay Bekleniyor
                                    @elseif($leaves[1]->status == 2)
                                        Onaylandı
                                    @else
                                        Reddedildi
                                    @endif
                                @endif
                            @else
                                Onay Bekleniyor
                            @endif </td>
                        <td class="space-left" align="center" height="30px"> @if(isset($leaves[2]))
                                @if($leaves[2]->type_authority == 3)
                                    @if($leaves[2]->status == 1)
                                        Onay Bekleniyor
                                    @elseif($leaves[2]->status == 2)
                                        Onaylandı
                                    @else
                                        Reddedildi
                                    @endif
                                @endif
                            @else
                                Onay Bekleniyor
                            @endif </td>
                    </tr>
                    <tr>
                        <td class="space-left" align="center" height="30px"> @if(isset($leaves[0]))
                                @if($leaves[0]->type_authority == 1)
                                    @if($leaves[0]->status == 1)
                                        Onay Bekleniyor
                                    @elseif($leaves[0]->status == 2)

                                        {{date('d/m/Y', strtotime($leaves[0]->accept_date))}}
                                    @else
                                        {{date('d/m/Y', strtotime($leaves[0]->decline_date))}}
                                    @endif
                                @endif
                            @else
                                Onay Bekleniyor
                            @endif </td>
                        <td class="space-left" align="center" height="30px"> @if(isset($leaves[1]))
                                @if($leaves[1]->type_authority == 2)
                                    @if($leaves[1]->status == 1)
                                        Onay Bekleniyor
                                    @elseif($leaves[1]->status == 2)
                                        {{date('d/m/Y', strtotime($leaves[1]->accept_date))}}
                                    @else
                                        {{date('d/m/Y', strtotime($leaves[1]->decline_date))}}
                                    @endif
                                @endif
                            @else
                                Onay Bekleniyor
                            @endif </td>
                        <td class="space-left" align="center" height="30px"> @if(isset($leaves[2]))
                                @if($leaves[2]->type_authority == 3)
                                    @if($leaves[2]->status == 1)
                                        Onay Bekleniyor
                                    @elseif($leaves[2]->status == 2)
                                        {{date('d/m/Y', strtotime($leaves[2]->accept_date))}}
                                    @else
                                        {{date('d/m/Y', strtotime($leaves[2]->decline_date))}}
                                    @endif
                                @endif
                            @else
                                Onay Bekleniyor
                            @endif </td>
                    </tr>
                    </tbody>
                </table>


</body>
</html>
