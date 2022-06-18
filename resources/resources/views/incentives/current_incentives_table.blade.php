<table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
    <thead>
    <tr align="center">
        <th style="font-weight:bold;color: black;text-align: center;" colspan="14">MORE PAYROLL | Bordrolama ve Teşvik Hizmetleri
        </th>
    </tr>
    <tr align="center">
        <th style="background-color: #424b52;font-weight:bold;color: #ffffff;text-align: center;" colspan="14">{{ $incitementDate }} - {{ $company['name'] }}
        </th>
    </tr>
    <tr align="center" style>
        <th style="background-color: #d9d9d9;text-align: center;" colspan="14">{{ $company['registry_id'] }}</th>
    </tr>

    <tr align="center" style>
        <th style="background-color: #d9d9d9;text-align: right;" colspan="12"><strong>TOPLAM ÇALIŞAN SAYISI</strong></th>
        <th style="background-color: #d9d9d9;text-align: center;" colspan="2"><strong>{{ $totalStaff }} KİŞİ</strong></th>
    </tr>
    <tr align="center">
        <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">6111 SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
        </th>
    </tr>
    <tr>
        <th style="background-color: #d9d9d9;">T.C. Kimlik No</th>
        <th style="background-color: #d9d9d9;">Adı</th>
        <th style="background-color: #d9d9d9;">Soyadı</th>
        <th style="background-color: #d9d9d9;">Belge Türü</th>
        <th style="background-color: #d9d9d9;">Ücreti</th>
        <th style="background-color: #d9d9d9;">Önceki Kanun</th>
        <th style="background-color: #d9d9d9;">Kanun</th>
        <th style="background-color: #d9d9d9;">Brüt Kazanç</th>
        <th style="background-color: #d9d9d9;">Teşvik Hakedişi</th>
        <th style="background-color: #d9d9d9;">Gün</th>
        <th style="background-color: #d9d9d9;">Başlama</th>
        <th style="background-color: #d9d9d9;">Bitiş</th>
        <th style="background-color: #d9d9d9;">İşe Giriş</th>
        <th style="background-color: #d9d9d9;">Ortalaması</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($incitements['6111']))
    @foreach($incitements['6111'] as $incitement)
        <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
            <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
        </tr>
    @endforeach
    @endif
    @if(isset($notDaysInfo['notDayPersonals']['6111']))
        <tr>
            <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
        </tr>
        @foreach($notDaysInfo['notDayPersonals']['6111'] as $min_personel)
            @foreach($min_personel as $incitement)
                <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                    <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                    <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                    <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                </tr>
            @endforeach
        @endforeach
    @endif

    <tr>
        <td>#</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="4" style="text-align: right">TOPLAM KİŞİ SAYISI:</td>
        <td>@if(isset($incitements["6111"])){{ count($incitements["6111"]) }} @else 0 @endif</td>
    </tr>
    </tbody>
</table>
<div class="page_break"></div>
<table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
    <thead>
    <tr align="center">
        <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">7103 SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
        </th>
    </tr>
    <tr>
        <th style="background-color: #d9d9d9;">T.C. Kimlik No</th>
        <th style="background-color: #d9d9d9;">Adı</th>
        <th style="background-color: #d9d9d9;">Soyadı</th>
        <th style="background-color: #d9d9d9;">Belge Türü</th>
        <th style="background-color: #d9d9d9;">Ücreti</th>
        <th style="background-color: #d9d9d9;">Önceki Kanun</th>
        <th style="background-color: #d9d9d9;">Kanun</th>
        <th style="background-color: #d9d9d9;">Brüt Kazanç</th>
        <th style="background-color: #d9d9d9;">Teşvik Hakedişi</th>
        <th style="background-color: #d9d9d9;">Gün</th>
        <th style="background-color: #d9d9d9;">Başlama</th>
        <th style="background-color: #d9d9d9;">Bitiş</th>
        <th style="background-color: #d9d9d9;">İşe Giriş</th>
        <th style="background-color: #d9d9d9;">Ortalaması</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($incitements["27103"]))
        @foreach($incitements["27103"] as $incitement)
            <tr
                @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['27103']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['27103'] as $min_personel)
                @foreach($min_personel as $incitement)
                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                    </tr>

                @endforeach
            @endforeach
        @endif
    @endif
    @if(isset($incitements["17103"]))
        @foreach($incitements["17103"] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['17103']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['17103'] as $min_personel)
                @foreach($min_personel as $incitement)
                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif
    @endif
    @if(isset($incitements["7103"]))
        @foreach($incitements["7103"] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['7103']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['7103'] as $min_personel)
                @foreach($min_personel as $incitement)
                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif
    @endif
    @if(isset($incitements["14857"]))
        @foreach($incitements["14857"] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['14857']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['14857'] as $min_personel)
                @foreach($min_personel as $incitement)
                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif
    @endif
    @if(isset($totalIncitements['7103-count']))
        <tr>
            <td>#</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" style="text-align: right">TOPLAM KİŞİ SAYISI:</td>
            <td>{{ $totalIncitements['7103-count'] }}</td>
        </tr>
    @endif
    </tbody>
</table>
@if(isset($incitements['7252']))
    <br/>
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">7252 SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
            </th>
        </tr>
        <tr>
            <th style="background-color: #d9d9d9;">T.C. Kimlik No</th>
            <th style="background-color: #d9d9d9;">Adı</th>
            <th style="background-color: #d9d9d9;">Soyadı</th>
            <th style="background-color: #d9d9d9;">Belge Türü</th>
            <th style="background-color: #d9d9d9;">Hakediş Ücreti</th>
            <th style="background-color: #d9d9d9;">Önceki<br/>Kanun</th>
            <th style="background-color: #d9d9d9;">Kanun<br/>Numarası</th>
            <th style="background-color: #d9d9d9;">Ortalama<br/>Gün Sayısı</th>
            <th style="background-color: #d9d9d9;">Başlangıç<br/>Dönemi</th>
            <th style="background-color: #d9d9d9;">Bitiş<br/>Dönemi</th>
            <th style="background-color: #d9d9d9;">İşe Giriş<br/>Tarihi</th>
            <th style="background-color: #d9d9d9;">İşten Çıkış<br/>Tarihi</th>
            <th style="background-color: #d9d9d9;"></th>
            <th style="background-color: #d9d9d9;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($incitements['7252'] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tesvik"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['7252']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['7252'] as $min_personel)
                @foreach($min_personel as $incitement)
                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tesvik"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif

        @if(isset($totalIncitements['7252-count']))
            <tr>
                <td colspan="4" style="text-align: right;">TOPLAM</td>
                <td colspan="10" style="text-align: left;">{{ $totalIncitements['7252-count'] }}</td>
            </tr>
        @endif
        </tbody>
    </table>
@endif
@if(isset($incitements['27256']))
    <br/>
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">27256 SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
            </th>
        </tr>
        <tr>
            <th style="background-color: #d9d9d9;">T.C. Kimlik No</th>
            <th style="background-color: #d9d9d9;">Adı</th>
            <th style="background-color: #d9d9d9;">Soyadı</th>
            <th style="background-color: #d9d9d9;">Belge Türü</th>
            <th style="background-color: #d9d9d9;">Hakediş Ücreti</th>
            <th style="background-color: #d9d9d9;">Önceki<br/>Kanun</th>
            <th style="background-color: #d9d9d9;">Kanun<br/>Numarası</th>
            <th style="background-color: #d9d9d9;">İlave Olunması<br/>Gereken Sayı</th>
            <th style="background-color: #d9d9d9;">Başlangıç<br/>Dönemi</th>
            <th style="background-color: #d9d9d9;">Bitiş<br/>Dönemi</th>
            <th style="background-color: #d9d9d9;">İşe Giriş<br/>Tarihi</th>
            <th style="background-color: #d9d9d9;">İşten Çıkış<br/>Tarihi</th>
            <th style="background-color: #d9d9d9;"></th>
            <th style="background-color: #d9d9d9;"></th>
        </tr>
        <tr>
            <th style="background-color: #d9d9d9;" colspan="14">DESTEK SÜRESİ BİTTİKTEN SONRA ,KİŞİNİN İŞTEN ÇIKARILMADAN ÖNCE ORTALAMA GÜN SAYISINI KONTROL EDİNİZ.</th>
        </tr>
        </thead>
        <tbody>
        @foreach($incitements['27256'] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif></td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif></td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['27256']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['27256'] as $min_personel)
                @foreach($min_personel as $incitement)
                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif></td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and $multipleLaw[$incitement["tck"]]!=1) style="background-color: #8fdf82;" @endif></td>
                    </tr>
                @endforeach
            @endforeach
        @endif
        @if(isset($totalIncitements['27256-count']))
            <tr>
                <td colspan="4" style="text-align: right;">TOPLAM</td>
                <td colspan="10" style="text-align: left;">{{ $totalIncitements['27256-count'] }}</td>
            </tr>
        @endif
        </tbody>
    </table>
@endif
<br/>
<table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
    <thead>
    <tr align="center">
        <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">ÖNEMLİ BİLGİLENDİRME
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($personelOrtalamaTotal as $law_info => $law)
        @foreach($law as $min_personel_info => $min_personel)
            <tr>
                <td colspan="14">{{ $law_info }} nolu kanunda {{ $min_personel_info }} ortalamasına sahip {{ $min_personel }} kişi faydalanabilir.</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
<div class="page_break"></div>
@if(isset($notDaysInfo['notIncentives']))
    <br/>
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">TEŞVİKTEN FAYDALANAMAYAN PERSONEL VE NEDENLERİ
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($notDaysInfo['notIncentives'] as $law_info => $law)
            @foreach($law as $min_personel_info => $min_personel)
                @foreach($min_personel as $personel)
                    <tr>
                        <td colspan="14">{{ $law_info }} nolu kanunda {{ $min_personel_info }} ortalamasına sahip {{ $personel['name']. " ".$personel['surname'] }} isimli çalışan {{ ($min_personel_info-$totalStaff) }} yeni personelden sonra {{ $personel['tesvik'] }} TL tutarında teşvik hakkı kazanacaktır.</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>
@endif
