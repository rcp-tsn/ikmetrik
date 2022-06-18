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
            @foreach($metric_reports as $metric_report)
                <div class="col-sm-3 p5">
                    <a href="{{ route('sub-metrics', $metric_report->slug_en) }}" class="card card-custom metric-button-hover card-shadowless gutter-b" style="background-color: #f3f6f9;height: 125px;margin-bottom: 0px;">
                        <div class="card-body" style="padding: 1.5rem 1rem;text-align: center;">
                        <span class="svg-icon svg-icon-3x ml-n1" style="">
                            <img src="/icon/{{ $metric_report->icon }}" style="height: 48px;">
                        </span>
                            <div class="font-weight-bolder mb-2 mt-2" style="width: 100%;font-size: 17px;margin: 0 auto;text-align: center;">{{ $metric_report->name }}</div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@stop


