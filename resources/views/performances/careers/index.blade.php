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
        <div class="row">
            <div class="col-lg-9">
                <div class="card  gutter-b" style="background-color: cadetblue;margin-right: auto;margin-left: 60px;text-align: center;margin: 25px;">
                    <div class="container" style="padding: 15px;">
                        <div class="row" style="margin-bottom: 50px;">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-4" style="text-align: center"><label style="font-weight: bold;font-size: 30px; color: #FFFFFF">{{session()->has('working_title') ? session()->get('working_title').' Ünvanı İle İşlem Yapılacak' : 'Ünvan Seçiniz' }}</label></div>
                            <div class="col-lg-4">

                            </div>
                        </div>
                        <div class="row" >

                            @foreach($working_types as $type)
                                @if($type->id != 10)
                                <div class="col-xl-6">
                                    <!--begin::Stats Widget 4-->
                                    <div class="card card-custom card-stretch gutter-b"  >
                                        <a href="{{route('work_titles_setting',$type->slug_en)}}" disabled="disabled" class="card card-custom metric-button-hover card-shadowless gutter-b" style="height: 125px;margin-bottom: 0px;" >
                                            <!--begin::Body-->
                                            <div class="card-body d-flex align-items-center py-0 mt-8">
                                                <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5">
                                                    <label class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 text-hover-primary">{{ $type->name }}</label>
                                                    <span class="font-weight-bold text-muted font-size-lg">{{ $type->slug }}</span>
                                                </div>
                                                <img src="/icon/{{ $type->icon }}" alt="" class="align-self-end h-70px" style="margin-bottom: 20px">
                                            </div>
                                        </a>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Stats Widget 4-->
                                </div>
                                @endif
                            @endforeach
                                @foreach($working_types as $type)
                                    @if($type->id == 10)
                                        <div class="col-xl-12">
                                            <!--begin::Stats Widget 4-->
                                            <div class="card card-custom card-stretch gutter-b"  >
                                                <a href="{{route('work_titles_setting',$type->slug_en)}}" disabled="disabled" class="card card-custom metric-button-hover card-shadowless gutter-b" style="height: 125px;margin-bottom: 0px;" >
                                                    <!--begin::Body-->
                                                    <div class="card-body d-flex align-items-center py-0 mt-8">
                                                        <div class="d-flex flex-column flex-grow-1 py-2 py-lg-5">
                                                            <label class="card-title font-weight-bolder text-dark-75 font-size-h5 mb-2 text-hover-primary">{{ $type->name }}</label>
                                                            <span class="font-weight-bold text-muted font-size-lg">{{ $type->slug }}</span>
                                                        </div>
                                                        <img src="/icon/{{ $type->icon }}" alt="" class="align-self-end h-70px" style="margin-bottom: 20px">
                                                    </div>
                                                </a>
                                                <!--end::Body-->
                                            </div>
                                            <!--end::Stats Widget 4-->
                                        </div>
                                    @endif
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="margin-top: 27px;margin-right: 25px; height: 250px; background-position: right top; background-size: 30% auto; background-image: url(/assets/media/svg/shapes/abstract-4.svg)">

                    <!--begin::Body-->

                    <div class="card-body company-select-area d-flex align-items-center">
                        <div>

                            <h4 class="text-black font-weight-bolder line-height-lg mb-5">SEÇİLİ ÜNVAN: {{ session()->has('selectWorkTitleName') ? session()->get('selectWorkTitleName','name') : 'SEÇİM YAPINIZ' }}</h4>
                            @if(session()->has('selectWorkTitleName'))
                                <a href="{{ route('WorkTitleUnset')}}" class="btn font-weight-bolder text-uppercase btn-warning py-4 px-6">SEÇİMİ KALDIR</a>
                            @else
                                <a href="{{ route('carerr_select_worktitle') }}" class="btn font-weight-bolder text-uppercase btn-warning py-4 px-6">ÜNVAN SEÇİN</a>
                            @endif
                            <br/>
                            <br/>
                            <br/>
                        </div>

                    </div>
                    <br>

                    <!--end::Body-->
                </div>

                <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="margin-top: 27px;margin-right: 25px; height: 250px; background-position: right top; background-size: 30% auto; background-image: url(/assets/media/svg/shapes/abstract-4.svg)">
                    <!--begin::Body-->
                    <div class="card-body">
                        <a href="#" class="card-title font-weight-bold text-muted text-hover-primary font-size-h5">Meeting Schedule</a>
                        <div class="font-weight-bold text-black mt-9 mb-5">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 font-weight-bolder font-size-h5 m-0">Craft a headline that is informative
                            <br>and will capture readers</p>
                    </div>
                    <!--end::Body-->
                </div>
            </div>
        </div>


@stop


