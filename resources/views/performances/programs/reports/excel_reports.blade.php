<table class="table">

    <thead>
    <tr>
                    <th style="text-align: center;height: 80px;font-weight: bold;"><img src="{{public_path('/'.$company->logo)}}" height="80" width="80" alt=""></th>
                    <th colspan="15" style="text-align: center;height: 80px;font-weight: bold;">GENEL PERFORMANS DEĞERLENDİRME RAPORU</th>
    </tr>
    <tr style="width: 25px;height: 20px;background-color: #ffc107">
                   <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Adı Soyadı</th>
                  @foreach($program_types as $type)
                        <th style="width: 25px;height: 23px;background-color: #ffc107;text-align: center;font-weight: bold">{{$type->performance_type()}}</th>
                  @endforeach
                 <th style="width: 25px;;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Toplma Performans Puanı</th>
                 <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Puan Tanımı</th>
                 <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Zam Oranı</th>
                 <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Mevcut Brüt Ücret</th>
                 <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Yeni Brüt Ücret</th>
                 <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Şirket Performans Puan Ortalaması</th>
    </tr>
    </thead>

    <tbody>
    @foreach($employees as $key => $employee)
        <tr style="height: 25px">
            <td style="height: 25px">{{$employee->full_name}}</td>
            @foreach($program_types as $type)
                <td style="height: 25px">{{$puans[$employee->id][$type->performance_type_id][0]}}</td>
            @endforeach
            @php $sonuc = $sonuclar[$employee->id];@endphp
            <td>{{number_format($sonuc,2,',','.')}}</td>
            <td @if($sonuc <= 50) style="background-color: red;width: 25px" @elseif($sonuc > 50 and $sonuc <= 69) style="background-color: yellow;width: 25px" @elseif($sonuc > 69 and $sonuc <= 80) style="background-color: blue;width: 25px" @else style="background-color: #28a745;width: 25px"@endif>
                <?php

                    if ($sonuc <= 50)
                {
                    echo  'Beklentinin Altında';
                }
                elseif ($sonuc > 50 and $sonuc <= 69)
                {
                    echo  'Beklenen seviyeye yakın';
                }
                elseif ($sonuc > 69 and $sonuc <= 80)
                {
                    echo 'Beklenen seviyede';
                }
                else
                {
                    echo  'Beklenen seviyenin üstü';
                }
                ?>
            </td>

            <td>{{($zam_oran['max_zam'] * array_sum($employee_toplam_puan[$employee->id]))/100}}</td>
            <td>
                @if(isset($employee->employeeSalary->salary_type))
                @if($employee->employeeSalary->salary_type == 1)
                    {{number_format($employee->employeeSalary->salary / 0.71491 , 2 , ',','.')}}
                @else
                {{$employee->employeeSalary->salary}}
                    @endif
                @else
                    data Girilmemiş
                    @endif
            </td>
            <td>
                <?php
                if(isset($employee->employeeSalary->salary_type))
                {
                    if($employee->employeeSalary->salary_type == 1)
                    {
                        $brüt = $employee->employeeSalary->salary / 0.71491;
                        $oran =  ($zam_oran['max_zam'] * array_sum($employee_toplam_puan[$employee->id]))/100;
                        $brüt2 = ($brüt * $oran)/100;
                        echo  number_format($brüt + $brüt2 ,2, ',','.').' '.'₺';
                    }
                    else
                    {
                        $oran =  ($zam_oran['max_zam'] * array_sum($employee_toplam_puan[$employee->id]))/100;
                        $brüt = ($employee->employeeSalary->salary * $oran)/100;
                        echo number_format($brüt + $employee->employeeSalary->salary,2,',','.').' '.'₺';
                    }
                }
                else
                {
                    echo 'data Girilmemiş';
                }

                   ?>
            </td>
            <td>

                {{number_format(array_sum($company_toplam_puan) / count($employees),2,',','.')}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


<table class="table mt-10" style="border: 1px;width: 30%;" >
    <thead>

    </thead>
    <tbody>
    <tr>
        <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px">Puan Aralığı</td>
        <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px">Tanım</td>
        <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px"></td>
    </tr>
    <tr>
        <td style="font-size: 15px;font-weight: bold">0-50 arası</td>
        <td  style="font-size: 15px;font-weight: bold">Beklentinin Altında</td>
        <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: red;text-align: center;"></div></td>
    </tr>
    <tr>
        <td  style="font-size: 15px;font-weight: bold">50-69</td>
        <td  style="font-size: 15px;font-weight: bold">Beklenen Seviyeye Yakın</td>
        <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: yellow;text-align: center;"></div></td>
    </tr>
    <tr>
        <td  style="font-size: 15px;font-weight: bold">69-80 arasında</td>
        <td  style="font-size: 15px;font-weight: bold">Beklenen seviyede</td>
        <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: blue;text-align: center;"></div></td>
    </tr>
    <tr>
        <td  style="font-size: 15px;font-weight: bold">80-100 arasında</td>
        <td  style="font-size: 15px;font-weight: bold">Beklenen seviyenin üstünde</td>
        <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: #90ff90;text-align: center;"></div></td>
    </tr>
    </tbody>
</table>

<br>
<br>
<table class="table" style="border: 1px">
    <thead>
    <tr>
        <th colspan="6" style="background-color: orange;font-weight: bold;font-size: 15px;text-align: center" class="up-font">Rapor Notları</th>
    </tr>

    </thead>
    <tbody>
    @php $sayi = 1; @endphp
    @foreach($employees as $key => $employee)
        @if(count($type_puan[$employee->id]) > 0 )

            <tr>
                <td style="text-align: center">{{$sayi++}}.
                </td>
                <td colspan="5" style="font-weight: bold;text-align: center">{{$employee->full_name.' '.'Üst Yöneticisi Yada Ast Yöneticisi Olmadığından Muaf Tutuldu'}}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
