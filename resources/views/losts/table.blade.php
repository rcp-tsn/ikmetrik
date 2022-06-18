@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Callout-->
                    <div class="card card-custom wave wave-animate-slow wave-primary mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center p-5">
                                <!--begin::Icon-->
                                <div class="col-lg-0 mr-6">
													<span class="svg-icon svg-icon-primary svg-icon-4x">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"/>
        <rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"/>
        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
        <rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"/>
    </g>
</svg>
													</span>
                                </div>
                                <!--end::Icon-->
                                <!--begin::Content-->
                                <div class="col-lg-4 d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Cari Dönem Teşvikleri</a>
                                    <div class="text-dark-75">Seçili Şube İçin Cari Dönem Teşvik Listesi</div>
                                </div>
                                <div class="col-lg-8 d-flex align-items-center justify-content-lg-end">

                                </div>
                                <!--end::Content-->
                            </div>
                        </div>
                    </div>
                    <!--end::Callout-->
                </div>
            </div>
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-body">
@if(isset($errors))
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="font-weight:bold;color: black;text-align: center;" colspan="14">TARAMA HATALARI
            </th>
        </tr>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">SGK SİSTEMİNDE SORGULAMA HATASI
            </th>
        </tr>
        <tr>
            <th colspan="4"  style="background-color: #d9d9d9;">T.C</th>
            <th colspan="6"  style="background-color: #d9d9d9;">İSİM SOYİSİM</th>
            <th colspan="4"  style="background-color: #d9d9d9;">TEŞVİKTEN FAYDALANAMAMA OLASI NEDENİ</th>

        </tr>
        </thead>
        <tbody>
        @if(count($errors) > 0)
            @foreach($errors as $tc => $error )
                @if(isset($error[0]) and !empty($error[0]))

                    <tr>
                        <td style="text-align: center;font-size:15px;font-weight: bold " colspan="4">{{!empty($tc) ? $tc : null}}</td>
                        <td style="text-align: center;font-size:15px;font-weight: bold " colspan="6">{{!empty($error[1]) ? $error[1] : null}}</td>
                        <td style="text-align: center;font-size: 15px;font-weight: bold" colspan="4">{{isset($error[0]) and !empty($error[0])  ? $error[0] : null}}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
@endif

<br>


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

            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3 ) style="background-color: #8fdf82;"  @endif >
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
    @endif

    @if(isset($notDaysInfo['notDayPersonals']['6111']))
        <tr>
            <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
        </tr>
        @foreach($notDaysInfo['notDayPersonals']['6111'] as $min_personel)
            @foreach($min_personel as $incitement)


                    <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                        <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and  count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                        <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
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
                @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif  >
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['27103']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['27103'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)

                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;"  @endif>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["ucret"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
                        </tr>

                    @endforeach
                @endforeach
            @endforeach
        @endif
    @endif

    @if(isset($incitements["17103"]))
        @foreach($incitements["17103"] as $incitement)
            <tr

                @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  or count($multipleLaw[$incitement["tck"]]) == 3 ) style="background-color: #8fdf82;" @endif >
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['17103']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['17103'] as $min_personel)
                @foreach($min_personel as $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3   ) style="background-color: #8fdf82;" @endif >
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3 ) style="background-color: #8fdf82;" @endif > {{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3 ) style="background-color: #8fdf82;" @endif > {{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["ucret"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {!! $incitement["tesvik"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif > {{ $incitement["min_personel"] }}</td>
                        </tr>
                @endforeach
            @endforeach
        @endif
    @endif

    @if(isset($incitements["7103"]))
        @foreach($incitements["7103"] as $incitement)

            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['7103']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['7103'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["ucret"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{!! $incitement["old_law"] !!}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3   ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    @endif

    @if(isset($incitements["14857"]))
        @foreach($incitements["14857"] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  )  style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['14857']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['14857'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3   ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                        </tr>
                    @endforeach
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
            <td> @if(isset($incitements["7103"])){{ count($incitements["7103"]) }} @elseif(isset($incitements["17103"])) {{ count($incitements["17103"]) }} @elseif(isset($incitements["14857"])) {{ count($incitements["14857"]) }} @elseif(isset($incitements["27103"])) {{ count($incitements["27103"]) }} @else 0 @endif </td>
        </tr>
    @endif
    </tbody>
</table>
@if(isset($incitements['3294']))
    <br/>
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">3294 SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
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
        @foreach($incitements['3294'] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['3294']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['3294'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["tesvik"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif >{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if(isset($totalIncitements['3294-count']))
            <tr>
                <td colspan="4" style="text-align: right;">TOPLAM</td>
                <td colspan="10" style="text-align: left;">@if(isset($incitements["3294"])) {{ count($incitements["3294"]) }} @else 0 @endif</td>
            </tr>
        @endif
        </tbody>
    </table>
@endif


@if(isset($incitements['7319']))
    <br/>
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">7319 SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
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
        @foreach($incitements['7319'] as $incitement)
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or  isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3   ) style="background-color: #8fdf82;" @endif >
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["ucret"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{!! $incitement["ucret"] + $incitement["ikramiye"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]]) or isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3  ) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['7319']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['7319'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["tesvik"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if(isset($totalIncitements['7319-count']))
            <tr>
                <td colspan="4" style="text-align: right;">TOPLAM</td>
                <td colspan="10" style="text-align: left;">@if(isset($incitements["7319"])) {{ count($incitements["7319"]) }} @else 0 @endif</td>
            </tr>
        @endif
        </tbody>
    </table>
@endif




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
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["tesvik"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['7252']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['7252'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["tesvik"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["days"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and !in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) ==3 ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if(isset($totalIncitements['7252-count']))
            <tr>
                <td colspan="4" style="text-align: right;">TOPLAM</td>
                <td colspan="10" style="text-align: left;">@if(isset($incitements["7252"])) {{ count($incitements["7252"]) }} @else 0 @endif</td>
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
            <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and     in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif></td>
                <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif></td>
            </tr>
        @endforeach
        @if(isset($notDaysInfo['notDayPersonals']['27256']))
            <tr>
                <td colspan="13">ORTALAMA SAYISI TUTUYOR FAKAT AYNI ORTALAMA SAYISI OLAN KİŞİLERLE TOPLAM ÇALIŞAN SAYISINA DENK DÜŞMEYEN ÇALIŞAN LİSTESİ:</td>
            </tr>
            @foreach($notDaysInfo['notDayPersonals']['27256'] as $min_personel)
                @foreach($min_personel as $tck)
                    @foreach($tck as $document_type => $incitement)
                        <tr @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1  ) style="background-color: #8fdf82;" @endif     @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["tck"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["name"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["surname"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["document_type"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{!! $incitement["tesvik"] !!}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["old_law"] }}</td>
                            <td @if($incitement['law_different']) style="background-color: #58afd5;" @endif>{{ $incitement["law"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["min_personel"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["job_start"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif>{{ $incitement["job_finish"] }}</td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif></td>
                            <td @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) !=1 and in_array("27256", $multipleLaw[$incitement["tck"]])  ) style="background-color: #8fdf82;" @endif @if(isset($multipleLaw[$incitement["tck"]]) and count($multipleLaw[$incitement["tck"]]) == 3) style="background-color: #8fdf82;" @endif></td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif

        @if(isset($totalIncitements['27256-count']))
            <tr>
                <td colspan="4" style="text-align: right;">TOPLAM</td>
                <td colspan="10" style="text-align: left;">@if(isset($incitements["27256"])){{ count($incitements["27256"]) }} @else 0 @endif</td>
            </tr>
        @endif
        </tbody>
    </table>
@endif
<br/>


    @if(isset($leakage_incitements))
        <br/>
        <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
            <thead>
            <tr align="center">
                <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">KAYIP KAÇAKLAR SAYILI TEŞVİKTEN YARARLANDIRILABİLECEK PERSONELLER
                </th>
            </tr>
            <tr>
                <th style="background-color: #d9d9d9;">T.C. Kimlik No</th>
                <th style="background-color: #d9d9d9;">Adı</th>
                <th style="background-color: #d9d9d9;">Soyadı</th>
                <th style="background-color: #d9d9d9;">Belge Türü</th>
                <th style="background-color: #d9d9d9;">Yararlanma Durumunda Hakediş Ücreti</th>
                <th style="background-color: #d9d9d9;">Önceki<br/>Kanun</th>
                <th style="background-color: #d9d9d9;">Kanun<br/>Numarası</th>
            </tr>
            <tr>
                <th style="background-color: #d9d9d9;" colspan="14">DESTEK SÜRESİ BİTTİKTEN SONRA ,KİŞİNİN İŞTEN ÇIKARILMADAN ÖNCE ORTALAMA GÜN SAYISINI KONTROL EDİNİZ.</th>
            </tr>
            </thead>
            <tbody>

            @foreach($leakage_incitements as $incitement)
                <tr>
                    <td>{{$incitement['tck']}}</td>
                    <td>{{$incitement['name']}}</td>
                    <td>{{$incitement['surname']}}</td>
                    <td>{{$incitement['document_type']}}</td>
                    <td>{{$incitement['lucraite_money']}}</td>
                    <td>{{$incitement['old_law']}}</td>
                    <td>{{$incitement['law']}}</td>

                </tr>
            @endforeach
                <tr>
                    <td colspan="4" style="text-align: right;">TOPLAM</td>
                    <td colspan="10" style="text-align: left;">@if(isset($leakage_incitements)){{ count($leakage_incitements) }} @else 0 @endif</td>
                </tr>
            </tbody>
        </table>
    @endif
    <br/>



@if(isset($personelOrtalamaTotal))
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
@endif
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
            @foreach($law as $min_personel_info => $personels)
                    @foreach($personels as $personel)

                        <tr>
                            <td colspan="14">{{ $law_info }} nolu kanunda {{ $min_personel_info }} ortalamasına sahip {{ $personel['name']. " ".$personel['surname'] }} isimli çalışan {{ ($min_personel_info - $totalStaff) }} yeni personelden sonra {{ $personel['tesvik'] }} TL tutarında teşvik hakkı kazanacaktır.</td>
                        </tr>
                @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>
@endif
<div class="page_break"></div>
@if(isset($incentives_finish))
    <br/>
    <table width="100%" border="1" cellspacing="1" cellpadding="0" class="table table-bordered">
        <thead>
        <tr align="center">
            <th style="background-color: #f5321c;font-weight:bold;color: white;text-align: center;" colspan="14">KANUN NUMARASI DEĞİŞENLER</th>
        </tr>
        <tr>
            <th>Tc</th>
            <th>İsim</th>
            <th>Soyisim</th>
            <th>Eski Kanun</th>
            <th>Yeni Kanun</th>
        </tr>
        </thead>
        <tbody>
        @foreach($incentives_finish as $key => $finish)

            <tr>
                <td>{{$finish[0]}}</td>
                <td>{{$finish[1]}}</td>
                <td>{{$finish[2]}}</td>
                <td>{{$finish[3]}}</td>
                <td>5510</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
