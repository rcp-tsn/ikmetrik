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
                        <div class="card-header">
                            <h3 class="card-title" style="font-size: 15px;font-weight: bold">
                                Performans Program Zam Oranı Belirle
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
{{--                        {!! Form::open(['route' => 'performances.interest.create', 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}--}}
                        <div class="card-body">
                            @include('partials.alerts.error')
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-weight: bold;font-size: 15px;">Performans Programı Seçiniz</label>
                                    {!! Form::select('performances',$programs,null,['class'=>'form-control selectpicker performance_id','data-live-search'=>'true' ,'']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-weight: bold;font-size: 15px;">Minimum Zam Oranı</label>
                                        {!! Form::text('min_zam',null,['class'=>'form-control min_zam']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold;">Max Zam Oranı</label>
                                        {!! Form::text('max_zam',null,['class'=>'form-control max_zam','placholder'=>'Min Zam']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">

                            <div class="form-group">
                                <label style="font-weight: bold">İşlem Durumu</label>
                            <div class="progress mt-10 mb-10" style="height: 30px;">

                                    <div class="progress-bar progress_class" role="progressbar" @if(isset($program)) style="width: 100%;background-color: orange;font-size: 15px;" @endif style="width:0%;background-color: orange; font-size: 25px;font-weight: bold" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">@if(isset($program)) 100% @else 0% @endif</div>

                                </div>
                            </div>
                            <div class="mt-10"style="text-align: center" id="success">
                                <div class="alert alert-success"style="font-weight: bold;font-size: 25px;">
                                    <strong>Performans Zammınız Tüm Personellere Performans Puanı Oranında Dağıtılmıştır</strong>
                                </div>
                            </div>

                            <div class="card-toolbar mb-auto">
                                <div class="row">
                                    <div class="form-group">
                                        <div class="button_html">

                                        </div>
                                    </div>

                                </div>

                            </div>


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
                                    }
                                    #customers td
                                    {

                                    }
                                </style>
                            <table class="table " id="customers">
                                <thead>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <table class="table customer2 mt-10" style="border: 1px;width: 30%;" >
                                <thead>

                                </thead>
                                <tbody>
                                <tr>
                                    <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px">Puan Aralığı</td>
                                    <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px">Tanım</td>
                                    <td style="background-color: #0BB7AF;font-weight: bold;font-size: 15px"></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;font-weight: bold">0-50 arası</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklentinin Altında</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: red;text-align: center;"></div></td>
                                </tr>
                                <tr>
                                    <td  style="font-size: 15px;font-weight: bold">50-69</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklenen Seviyeye Yakın</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: yellow;text-align: center;"></div></td>
                                </tr>
                                <tr>
                                    <td  style="font-size: 15px;font-weight: bold">69-80 arasında</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklenen seviyede</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: blue;text-align: center;"></div></td>
                                </tr>
                                <tr>
                                    <td  style="font-size: 15px;font-weight: bold">80-100 arasında</td>
                                    <td  style="font-size: 15px;font-weight: bold">Beklenen seviyenin üstünde</td>
                                    <td  style="font-size: 15px;font-weight: bold"><div style="height: 30px;width: 30px;background-color: #90ff90;text-align: center;"></div></td>
                                </tr>
                                </tbody>
                            </table>



                            <div class="button">
                                <button type="button"  id="save" class="btn btn-success mr-2">Performans Zam Uygula</button>
                                <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                            </div>


                        </div>
{{--                    {!! Form::close() !!}--}}
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
        $("#success").hide();
        $("#zam").hide();
        $("#customers").hide();
        $(".customer2").hide();
        $("#save").click(function ()
        {
            var performances = $('select[name=performances] option').filter(':selected').val()
            var min_zam = $(".min_zam").val();
            var max_Zam = $(".max_zam").val();

            $(".progress_class").css("width","40%");
            $(".progress_class").html("40%");

            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/performance-interest-create',
                data: {
                    min_zam:min_zam,
                    max_zam:max_Zam,
                    performances:performances,
                },
                success: function (datas) {
                    $(".button").hide();
                    $.each(datas['tables'], function (i, item) {
                        $('#customers tbody').append(item);
                    });
                    $.each(datas['table_thead'], function (i, item2) {
                        $('#customers thead').append(item2);
                    });
                    $("div.button_html").html(datas['html'])

                },
            });

            window.setTimeout(function(){
                $(".progress_class").css("width","60%");
                $(".progress_class").html("60%");
            }, 1000);
            window.setTimeout(function(){
                $(".progress_class").css("width","80%");
                $(".progress_class").html("80%");
            }, 2000);
            window.setTimeout(function(){
                $(".progress_class").css("width","100%");
                $(".progress_class").html("100%");
                $("#customers").show();
                $(".customer2").show();
            }, 3000);







        });
    </script>
    <script>
        "use strict";
        var KTDatatablesAdvancedColumnRendering = function() {

            var init = function() {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    paging: true
                });

                $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
            };

            return {

                //main function to initiate the module
                init: function() {
                    init();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTDatatablesAdvancedColumnRendering.init();
        });
    </script>
    <script>
        $(document).on("click", ".performanceInterestSave", function () {
            var url = $(this).data('url');
            swal({
                title: "Eminmisiniz?",
                text: "Bu İşlem geri alınamaz. Tüm Çalışanların Maaş Bilgileri Güncellencek!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href=url;
                        swal("Tebrikler! İşleminiz Başarıyla Gerçekleştirldi!", {
                            icon: "success",
                        });
                    } else {
                        swal("Üzgünüz İşleminizi Yapamadık!");
                    }
                });
        });
    </script>
    @endsection

