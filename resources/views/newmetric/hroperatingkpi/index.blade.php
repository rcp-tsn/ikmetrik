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
                <a href="/laborlossrate" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/1.png">
                    İş Gücü Kayıp Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/transferrateincompany" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/7.png">
                    Şirket İçi Nakil Oranı
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/taskdefinitionchart" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/14.png">
                    Görev Tanımı Grafiği
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="#" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/20.png">
                    Giriş/Çıkış Kontrolü
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/agedistributionchart" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/21.png">
                    Yaş Dağılımı Grafiği
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5" style="display: none;">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/genderdistributionchart" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/22.png">
                    Cinsiyet Dağılım Grafiği
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/educationlevelchart" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/23.png">
                    Eğitim Seviyesi Grafiği
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
        <div class="col-sm-4 p5">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline btn btn-icon btn-light h-40px" data-toggle="tooltip" title="" data-placement="left" style="width: 100%;height: 48px !important;display: block;text-align: left;">
                <a href="/disabilityassessment" class="metrikbutton" aria-haspopup="true" aria-expanded="false">
                    <img src="/icon/30.png">
                    Engelli Uygunluk Testi
                </a>
            </div>

                    <!--end::Dropdown-->
        </div>
   
    </div>    
</div>
@stop


