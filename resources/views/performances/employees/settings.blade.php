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
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4" style="text-align: center"><label style="font-weight: bold;font-size: 17px;">@include('partials.alerts.error')</label></div>
        <div class="col-lg-4"></div>
    </div>

    <div class="card card-custom card-stretch gutter-b" style="background-color: cadetblue;margin-right: auto;margin-left: auto;text-align: center;width: 80%">
        <div class="container" style="padding: 15px;">
            <div class="row" style="margin-bottom: 50px;">
                <div class="col-lg-4"><label style="font-weight: bold;font-size: 15px; color: #FFFFFF">Seçili Personel {{$employee->full_name}}</label></div>
                <div class="col-lg-4" style="text-align: center"><label style="font-weight: bold;font-size: 30px; color: #FFFFFF">Personel Uygulamaları</label></div>
                <div class="col-lg-4"></div>
            </div>
            <div class="row" >

                @foreach($performances as $performance)
                        <div class="col-xl-4">
                            <!--begin::Stats Widget 4-->
                            <div class="card card-custom card-stretch gutter-b"  >
                                <a href="{{ route('employee_settings', [ 'type' => $performance->slug_en , 'id' => createHashId($id),'page'=>1 ]) }}" disabled="disabled" class="card card-custom metric-button-hover card-shadowless gutter-b" style="height: 125px;margin-bottom: 0px;" >
                                    <!--begin::Body-->
                                    <div class="card-body d-flex align-items-center py-0 mt-8">
                                        <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5">
                                            <label class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 text-hover-primary">{{ $performance->name }}</label>
                                            <span class="font-weight-bold text-muted font-size-lg">{{ $performance->name }} İçin Gerekli Yönetici ve Personel Atamaları</span>
                                        </div>
                                        <img src="/icon/{{ $performance->icon }}" alt="" class="align-self-end h-70px" style="margin-bottom: 20px">
                                    </div>
                                </a>
                                <!--end::Body-->
                            </div>
                            <!--end::Stats Widget 4-->
                        </div>

                @endforeach



            </div>
        </div>
    </div>

@stop


