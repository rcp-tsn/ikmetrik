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
                            <h3 class="card-title">
                                Yeni Döküman Oluştur
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => 'personelFiles.store', 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}
                        <div class="">
                            @include('partials.alerts.error')
                            @include('personelFiles.form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">{{ __('global.buttons.SaveButtonText') }}</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>

                    {!! Form::close() !!}
                    @include('personelFiles.InsertModal.fileType')
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

@push('scripts')
    <script>

        $( "#btn-filter" ).click(function() {
            var  id =  $("#sgk_company").val();
            var department_id = $("#department").val();
            var sayi = $(".type_applicant").length;

            if(sayi > 0 )
            {
                $(".type_applicant").append().remove();
            }

            $.ajax({
                type: "GET",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/ajax/employee/filter/'+ id +'/'+department_id,
                success: function (datas) {
                    var items = '';
                    $.each(datas['data'], function (i, item) {
                        $('#applicant_employee tbody').append(item);
                    });
                },
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            $('#allSelect').click(function(event) {  //on click
                if(this.checked) { // check select status
                    $(":checkbox").attr("checked", true);
                }else{
                    $(":checkbox").attr("checked", false);
                }
            });

        });
    </script>

    <script>
        $('#search').keyup(function () {

            var tg = $('.type_applicant').length;
            $("tr.type_applicant div:gt(" + tg +  ")").show();

            if ($('#search').val().length < 1) {
                tg = $('.type_applicant');
                $("tr.item_employee div:gt(" + tg +  ")").hide();
                var veriSayisi = 13;
                var sayi = 4;
                $("tr.type_applicant tr").hide();
                var indis = 1;
                var deger = veriSayisi * sayi;
                var gt = deger * indis;
                for (var i = gt - deger ; i < gt; i++ )
                {
                    $("tr.type_applicant tr:eq(" + i +  ")").show();
                }
                return;
            }
            $('.type_applicant').hide();

            var txt = $('#search').val();
            $('.type_applicant').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                    $(this).show();
                }
            });
            var t = $('.type_applicant:visible');
            $(".counter").html("Toplam <strong>" + t.length + "</strong> kişi gösteriliyor");
        });
    </script>
@endpush




