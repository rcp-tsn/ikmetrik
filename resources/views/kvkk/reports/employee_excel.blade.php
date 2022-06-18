<style>
    .yazi-font
    {
        font-size: 15px;
        font-weight: bold;
        text-align: center;
    }
</style>

<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="2"><img src="{{$company->logo}}" alt="" height="50" width="250"></th>
        <th colspan="5" class="yazi-font">Kvkk Denetim Raporu</th>
        <th class="yazi-font">{{date('d/m/Y')}}</th>
    </tr>
    <tr>
        <th class="yazi-font">Sıra</th>
        <th class="yazi-font">Adı Soyadı</th>
        @foreach($companyDocuments as $document)
            <th class="yazi-font">{{isset($document->document) ? $document->document->name  : ' '}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php $sira = 0; @endphp
    @foreach($employees as $employee)
            <tr>
                    <td class="yazi-font">{{$sira++}}</td>
                    <td class="yazi-font">{{$employee->full_name}}</td>

                @foreach($companyDocuments as $document)

                    <td class="yazi-font">{{$employeeDocument[$employee->id][$document->id]}}</td>
                @endforeach
            </tr>
    @endforeach
    </tbody>
</table>
