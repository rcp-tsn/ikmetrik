@extends('layouts.metric')
@section('content')

<style type="text/css">
    .p5{
        padding: 5px 15px;
    }
    .metrikbutton{
        width: 100%;height: 48px !important;text-align: left;padding-left: 10px;font-weight: 700;
    }
    .metrikbutton img{
        padding: 5px;height: 48px;
    }
</style>
<div class="container" style="padding: 30px;">
    <div class="row">
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/workaccidentrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/3.png">
                    İş Kazası Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/turnoverrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/4.png">
                    Turnover Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/reasonofquitjob" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/8.png">
                    İşten Ayrılış Nedenleri
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/indisciplinerate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/9.png">
                    Disiplinsizlik Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/reportedannulledrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/10.png">
                    Bildirimli Fesih Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/jobcompliancerate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/33.png">
                    İşe Uyum Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/terminationchartforspecialreasons" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/15.png">
                    Özel Nedenlerle İşten Ayrılma Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/timeallocatedtoeducation" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/17.png">
                    Eğitime Ayrılan Süre Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/resignrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/18.png">
                    İstifa Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/missingdaycauses" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/19.png">
                    Eksik Gün Nedenleri
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/accidentfrequencyrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/24.png">
                    Kaza Sıklık Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/accidentweightrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/25.png">
                    Kaza Ağırlık Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/reportingrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/26.png">
                    Raporlama Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
    </div>    
</div>
@stop


