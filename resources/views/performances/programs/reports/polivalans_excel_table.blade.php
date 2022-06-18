<table class="table">

    <thead>
    <tr>
        <th style="text-align: center;height: 80px;font-weight: bold;"><img src="{{public_path('/'.$company->logo)}}" height="80" width="80" alt=""></th>
        <th colspan="4" style="text-align: center;height: 80px;font-weight: bold;"> PERFORMANS POLİVALANS RAPORU</th>
    </tr>
    <tr style="width: 25px;height: 20px;background-color: #ffc107">
        <th style="width: 25px;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Adı Soyadı</th>
        <th style="width: 25px;;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Performans Programı</th>
        <th colspan="3" style="width: 25px;;height: 20px;background-color: #ffc107;text-align: center;font-weight: bold">Alması Gereken Eğitim</th>
    </tr>
    </thead>

    <tbody>
    @php $sira = 1; @endphp
    @foreach($datas as $education)

        <tr class="">
            <td>{{$employee->full_name}}</td>
            <td>{{!empty($education['question']) ? $education['question'] : null }}</td>
            <td>{{!empty($education['puan']) ? $education['puan'] : null }}</td>
            <td>{{!empty($education['durum']) ? $education['durum'] : null }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


