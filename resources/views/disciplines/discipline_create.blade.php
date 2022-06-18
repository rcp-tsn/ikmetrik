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
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">
                    @include('partials.alerts.error')
                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Çalışan Disiplin Suçları</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                        <!--end:: Card header-->
                        <div class="card-body">
                            {!! Form::open(['route' => 'disciplines.store', 'files'=>'true', 'class' => 'form', 'id' => 'discipline_form']) !!}
                            <div class="card card-custom">
                                <div class="card-body">
                           <div class="container-fluid">

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">Personel Seçiniz</label>
                                        {!! Form::select('employee',$subordinates,null,['class'=>'form-control selectpicker', 'required'=>'required', 'id'=>'employee','data-live-search'=>'true']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">Tutanak Ekleri (Birden Fazla Eklenebilir)</label><br>
                                        <input type="file" name="files[]" required multiple>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-weight: bold;font-size: 15px;">Tarih</label>
                                        {!! Form::date('date',null,['class'=>'form-control typeahead-remote', 'required'=>'required','id'=>'discipline_date']) !!}
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">1.Şahit Adı Soyadı</label>
                                        {!! Form::select('sahit1',$subordinates,null,['class'=>'form-control selectpicker', 'required'=>'required', 'id'=>'employee','data-live-search'=>'true']) !!}

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold"> 2.Şahit Adı Soyadı</label>
                                        {!! Form::select('sahit2',$subordinates,null,['class'=>'form-control selectpicker', 'required'=>'required', 'id'=>'employee','data-live-search'=>'true']) !!}

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">Önerilen Ceza</label><br>
                                        {!! Form::select('proposed',config('variables.ceza_type'),null,['class'=>'form-control selectpicker  ', 'required'=>'required', 'id'=>'']) !!}
                                    </div>
                                </div>


                            </div>

                            <br>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 15px;font-weight: bold">Onaylama Yöntemi</label><br>
                                        {!! Form::select('accept_type',['1'=>'ISLAK İMZA','2'=>'ZAMAN DAMGASI'],null,['class'=>'form-control selectpicker  ', 'required'=>'required', 'id'=>'']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-weight: bold;font-size: 15px;">Olay Yeri</label>
                                        {!! Form::text('map',null,['class'=>'form-control', 'required'=>'required']) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label style="font-weight: bold;font-size: 15px;">Olay Tanımı</label>
                                        {!! Form::textarea('definition',null,['class'=>'form-control', 'required'=>'required']) !!}
                                    </div>
                                </div>

                            </div>
                           </div>
                           </div>
                            </div>
                            <br>

                                            <!--begin::Body-->
                                            <div class="card-body pt-0">
                                                <label style="font-size: 15px;font-weight: bold">Aşağıdan İşlenen Suçları Seçiniz!!</label>
                                                <table class="table table-bordered" id="kt_datatable">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Suç Bilgisi</th>
                                                        <th>Tür</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($company_disciplines as $ciplines)
                                                    <tr>
                                                        <td><label class="checkbox">
                                                                <input type="checkbox"  name="disciplines[{{$ciplines->id}}]">
                                                                <span></span>
                                                            </label></td>
                                                        <td><label class="mt-10">{{$ciplines->name}}</label></td>
                                                        <td><label class="mt-10">{{$ciplines->type}}</label></td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <!--end::Item-->
                                            </div>
                                            <!--end::Body-->
                        </div>
                        <div class="card-body">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                                <button type="submit"  class="btn btn-primary font-weight-bold">Kaydet</button>
                            </div>
                        </div>



                            {!! Form::close() !!}
                    </div>
                </div>
                <!--begin::Card body-->

                <!--end:: Card body-->
            </div>
            <!--end:: Card-->


            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>


    <!--end::Entry-->
@stop
@section('js')
{{--    <script>--}}

{{--            if ($('#search').val().length == 0)--}}
{{--            {--}}
{{--                $('.discipline_search').hide();--}}
{{--                $(".counter").html("Arama Yapınız Suçlar Listelencek !");--}}
{{--            }--}}
{{--            else--}}
{{--            {--}}
{{--                $(".counter").hide();--}}
{{--            }--}}

{{--        $('#search').keyup(function () {--}}
{{--            if ($('#search').val().length < 2) {--}}
{{--                var tg = $('.discipline_search');--}}
{{--                tg.hide();--}}
{{--                $(".counter").html("Arama Yapınız Suçlar Listelencek !");--}}
{{--                return;--}}
{{--            }--}}
{{--            else--}}
{{--            {--}}
{{--                $(".counter").hide();--}}
{{--            }--}}
{{--            $('.discipline_search').hide();--}}

{{--            var txt = $('#search').val();--}}
{{--            $('.discipline_search').each(function () {--}}
{{--                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {--}}
{{--                    $(this).show();--}}
{{--                }--}}
{{--            });--}}
{{--            var t = $('.discipline_search:visible');--}}

{{--        });--}}
{{--    </script>--}}



<script>
    "use strict";
    var KTDatatablesAdvancedColumnRendering = function() {

        var init = function() {
            var table = $('#kt_datatable');

            // begin first table
            table.DataTable({
                responsive: true,
                paging: false
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
    $(".aktif").click(function ()
    {
        var page = $(this).html();
    });
@endsection



