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
                <a href="/overtimedayrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/2.png">
                    Fazla Mesai Günü Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/staffunitcostrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/5.png">
                    Personel Birim Maaliyet Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/costratebydepartment" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/6.png">
                    Departman Bazında Maliyet Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/incentiveutilizationrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/11.png">
                    Tesvik Faydalanma Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/personbasedincentiverate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/12.png">
                    Kişi Bazlı Teşvik Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/extrapaycostrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/13.png">
                    Ek Ödeme Maliyet Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/totalincentiveearnings" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/16.png">
                    Toplam Teşvik Kazancı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/severancepayburden" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/31.png">
                    Kıdem Tazminatı Yükü
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/noticecompensation" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/32.png">
                    İhbar Tazminatı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/educationcostperperson" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/27.png">
                    Kişi Başı Eğitim Maliyeti
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/dutywagechart" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/28.png">
                    Görev Ücret Matrisi
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/rateofcosttototalincome" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/29.png">
                    Toplam Ücretin Gelire Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
    </div>    
</div>
@stop


