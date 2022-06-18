<style>
    .table td
    {
        text-align: center;
        font-size: 17px;
        font-weight: bold;
    }
</style>
<table class="table" id="excel">

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
    @foreach($datas as $name => $educations)
    <tr style="border:1px solid black">
        <td style="font-weight: bold;text-align: center;vertical-align: middle;height: 0px;width: 15px;border:1px solid black" rowspan="4">{{$name}}</td>
        <td style="font-weight: bold;text-align: center;border:1px solid black" rowspan="4">Performans Programı</td>
        <td style="" colspan="3"></td>

    </tr>
    @foreach($educations as $education)
    <tr style="border: 1px solid black">
            <td style="border:1px solid black">{{!empty($education['question']) ? $education['question'] : null }}</td>
            <td style="border:1px solid black">{{!empty($education['puan']) ? $education['puan'] : null }}</td>
            <td style="border:1px solid black">{{!empty($education['durum']) ? $education['durum'] : null }}</td>

        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>


