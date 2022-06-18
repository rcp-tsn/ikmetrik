@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                        <!--begin::Card header-->
                        <div class="card-header" >
                            <h3 class="card-title" style="font-size: 15px;font-weight: bold">
                                Performans Program Zam Oranı Belirle
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>
                        <style>
                            #label
                            {
                                font-size: 15px;
                                font-weight: bold;
                            }
                        </style>
                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => 'performance.seyyanen.create', 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}
                        <div class="card-body">
                            @include('partials.alerts.error')
                            <div class="row">

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label id="label">Çalışan Seçiniz</label>
                                        {!! Form::select('employee',$employees,null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'employees']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label id="label">Maaşı /  <span class="salary_type"></span></label>
                                        {!! Form::text('salary',null,['class'=>'form-control ','id'=>'salary','readonly']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label id="label">İlave Zam Oranı</label>
                                        {!! Form::text('zam_oran',null,['class'=>'form-control zam_oran','placholder'=>'Min Zam']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label id="label">Yeni Maaş / <span class="salary_type"></span></label>
                                        <input type="text" name="new_maas" placeholder="Yeni Maaş" class="form-control" value=""  readonly id="new_salary">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="success">
                            <div class="alert alert-success">
                                <strong>Performans Zammınız Tüm Personele Performans Puanı Oranında Dağıtıldı.</strong>
                            </div>
                        </div>
                        <div class="card-footer">
                                <div class="progress mt-10 mb-10" style="height: 30px">
                                    <div class="progress-bar progress_class" role="progressbar" @if(isset($datas)) style="width: 100%;background-color: orange;font-size: 15px;" @endif style="width:0%;background-color: orange; font-size: 25px;font-weight: bold" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">@if(isset($datas)) 100% @else 0% @endif</div>
                                </div>
                            @if(isset($datas))
                                <style>
                                    #customers {
                                        font-family: Arial, Helvetica, sans-serif;
                                        border-collapse: collapse;
                                        width: 100%;
                                    }

                                    #customers td, #customers th {
                                        border: 1px solid #ddd;
                                        padding: 8px;
                                        text-align: center;
                                    }

                                    #customers tr:nth-child(even){background-color: #f2f2f2;}

                                    #customers tr:hover {background-color: #ddd;}

                                    #customers th {
                                        padding-top: 12px;
                                        padding-bottom: 12px;
                                        background-color: #04AA6D;
                                        text-align: center;
                                        width: 25px;
                                        height: 20px;
                                        font-weight: bold;
                                        font-size: 15px
                                    }
                                    #customers td
                                    {
                                        width: 25px;
                                        height: 20px;
                                        text-align: center;
                                        font-weight: bold;
                                        font-size: 15px;
                                    }
                                </style>
                                <table class="table" id="customers">

                                    <thead>
                                    <tr>
                                        <th colspan="4"  style="text-align: center;height: 80px;font-weight: bold;font-size: 17px;">GENEL PERFORMANS DEĞERLENDİRME RAPORU</th>
                                    </tr>
                                    <tr style="background-color: #ffc107">
                                        <th style="background-color: #ffc107">Adı Soyadı</th>
                                        <th style="background-color: #ffc107">Eski Bürüt Maaş</th>
                                        <th style="background-color: #ffc107">Zam Oranı</th>
                                        <th style="background-color: #ffc107">Yeni Bürüt Maas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr style="height: 25px">
                                        <td >{{$datas['name']}}</td>
                                        <td >{{$datas['back_salary']}}</td>
                                        <td >{{$datas['zam_oran']}}</td>
                                        <td >{{$datas['new_salary']}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            @endif

                            @if(!isset($datas))
                            <button type="button"  id="save" class="btn btn-success mr-2">Seyyanen Zam Uygula</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                                @endif
                        </div>
                    {!! Form::close() !!}
                    <!--end:: Card body-->
                    </div>
                    <!--end:: Card-->
                </div>
            </div>
            <!--end::Row-->
            <!--end::Dashboard-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
@stop
@section('js')
    <script>
        $(document).ready(function ()
        {
            $("#employees").change(function(){
                $('#employees  > option:selected').each(function() {
                    var id = $(this).val();
                    $.ajax({
                        type: "GET",
                        url: '/employee-salary-interest/'+id,
                        success: function (salary) {
                            var id = $("#salary").val(salary['salary']);
                            $(".salary_type").html(salary['salary_type']);
                        },
                    });
                });
            });
        });

    </script>
    <script>

        $('body').on('change', '.zam_oran', function() {
            $('.zam_oran').each(function() {
             var salary = $("#salary").val();
             var sonuc = (salary * $(this).val())/100;
             var money = Number(salary);

             sonuc = money + sonuc;
             $("#new_salary").val(sonuc);
            });


        });

    </script>
    <script>
        $("#success").hide();
        $("#save").click(function ()
       {

               $(".progress_class").css("width","40%");
               $(".progress_class").html("40%");

           window.setTimeout(function(){
               $(".progress_class").css("width","60%");
               $(".progress_class").html("60%");
           }, 1000);
           window.setTimeout(function(){
               $(".progress_class").css("width","80%");
               $(".progress_class").html("80%");
           }, 4000);
           window.setTimeout(function(){
               $(".progress_class").css("width","100%");
               $(".progress_class").html("100%");
           }, 5000);

               window.setTimeout(function(){
                   $("#kt_form").submit();
               }, 6000);



       });
    </script>


@endsection
