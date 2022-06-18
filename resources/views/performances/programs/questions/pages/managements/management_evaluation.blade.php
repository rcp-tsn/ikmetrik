@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    <style>
        #sayfalama
        {
            padding: 0px 25px 25px 25px;
        }
        #sayfalama a
        {
            color: #FFFFFF; background-color: #0AA699;padding: 3px 7px; margin-right: 6px; text-decoration: none;

        }
        #sayfalama a.aktif
        {
            background: red;
        }
    </style>
    <!--begin::Entry-->
    @if(strlen(session()->get('success'))>0)

        @endif
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->

            <!--begin::Row-->
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Yönetim Değerlendirme</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">Personele Yönetimin Minimum 0 ile 100 Arasında Verdiği Puan</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-1">
                                    <div class="form-group">
                                        <label>listeleme</label>
                                        <select  class="custom-select custom-select-sm form-control form-control-sm" name="" id="type">
                                            <option value="">4</option>
                                            <option value="">20</option>
                                            <option value="">40</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-9"></div>
                                <div class="col-lg-2">
                                    <div class="form-group" >
                                        <label for="search">Personel Arama : </label>
                                        <input type="text" name="search" id="search" class="form-control search">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($employees as $key => $employee)
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 item_employee">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b card-stretch">
                                            <!--begin::Body-->
                                            <div class="card-body pt-4">
                                                <!--begin::Toolbar-->
                                                <div class="d-flex justify-content-end">
                                                </div>
                                                <!--end::Toolbar-->
                                                <!--begin::User-->
                                                <div class="d-flex align-items-end mb-7">
                                                    <!--begin::Pic-->
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Pic-->
                                                        <div class="flex-shrink-0 mr-4 mt-lg-0 mt-3">
                                                            <div class="symbol symbol-circle symbol-lg-75">
                                                                <img src="/{{$employee->avatar}}" alt="image">
                                                            </div>
                                                            <div class="symbol symbol-lg-75 symbol-circle symbol-primary d-none">
                                                                <span class="font-size-h3 font-weight-boldest">JM</span>
                                                            </div>
                                                        </div>
                                                        <!--end::Pic-->
                                                        <!--begin::Title-->
                                                        <div class="d-flex flex-column">
                                                            <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">{{$employee->first_name}} {{$employee->last_name}}</a>
                                                            <span class="text-muted font-weight-bold">{{$employee->working_title->name}}</span>
                                                        </div>
                                                        <!--end::Title-->
                                                    </div>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::User-->
                                                <!--begin::Desc-->
                                                <p class="mb-7">
                                                    <a href="#" class="text-primary pr-1">{{$employee->email}}</a></p>
                                                <!--end::Desc-->
                                                <!--begin::Info-->
                                                <div class="mb-7">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-dark-75 font-weight-bolder mr-2">Puan:</span>
                                                        <div class="text-muted text-hover-primary" id="completed_education_show">{{!empty($employee->management_puan($id,$employee->id)) ? $employee->management_puan($id,$employee->id) : 'Puan Girilmemiş'}}</div>
                                                    </div>
                                                </div>
                                                <!--end::Info-->
                                                <a href="#" data-id="{{$employee->id}}" data-name="{{$employee->first_name}}{{$employee->last_name}}"    class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4 scholl">Puan Giriniz</a>
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                        <!--end::Card-->

                                    </div>
                                    <!--end profil-->
                                @endforeach

                            </div>



                            <div id="sayfalama" style="text-align: -webkit-right">

                            </div>
                        </div>

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


    <div class="modal fade" id="schollModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="/ajax-management-employee-puans" method="post" id="form_management" >
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                    <div class="form-group">
                        <label>Personel Adı Soyadı </label>
                        {!! Form::text('employee',null,['class'=>'form-control selectpicker', 'id'=>'employee','data-live-search'=>'true']) !!}

                    </div>
                        </div>

                        <input type="hidden" name="employee_id" value="" id="employee_id">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Puan Giriniz</label>
                                {!! Form::number('puan',null,['class'=>'form-control','id'=>'puan']) !!}
                            </div>
                        </div>

                        <input type="hidden" name="program_id" id="program_id" value="{{$id}}">
                        <input type="hidden" name="page" id="page" value="">

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                    <button type="button" id="scholl_update" class="btn btn-primary font-weight-bold">Kaydet</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!--end::Entry-->
@stop

@section('js')
    <script>

    </script>
    <script>
        $(".scholl").on('click',function ()
        {

            var employee_name =  $(this).data('name')

            var employee_id = $(this).data('id');
            $('#schollModal').modal('show');
            $("#employee").val(employee_name);
            $("#employee_id").val(employee_id);
            var page = $(".aktif").html();
            $("#page").val(page);

            $("#scholl_update").on('click',function ()
            {


                var puan =  $("#puan").val();
                var program_id =  $("#program_id").val();
                $('#schollModal').modal('hide');
                $.ajax({
                    type: "POST",
                    url: '/ajax-management-employee-puans',
                    data: {
                        _token: "{{csrf_token()}}",
                        employee_id:employee_id,
                        puan:puan,
                        program_id:program_id
                    },
                    success: function (data) {
                        Swal.fire({
                            position: "top-right",
                            icon: "success",
                            title: 'Güncelleme Başarılı Sayfa Yenilenince Veriler Değişecek',
                            showConfirmButton: false,
                            timer: 3500
                        });
                        window.setTimeout(function(){
                            window.location.reload();
                        }, 2000);

                    },

                    error: function (error) {
                        alert(error);
                    }
                });
            });
        });
    </script>

    <script>
        $('#search').keyup(function () {

            var tg = $('.item_employee').length;
            $("div.item_employee div:gt(" + tg +  ")").show();

            if ($('#search').val().length < 1) {
                tg = $('.item_employee');
                $("div.item_employee div:gt(" + tg +  ")").hide();
                var veriSayisi = 12;
                var sayi = 4;
                $("div.item_employee div").hide();
                var indis = 1;
                var deger = veriSayisi * sayi;
                var gt = deger * indis;
                for (var i = gt - deger ; i < gt; i++ )
                {
                    $("div.item_employee div:eq(" + i +  ")").show();
                }
                return;
            }
            $('.item_employee').hide();

            var txt = $('#search').val();
            $('.item_employee').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1) {
                    $(this).show();
                }
            });
            var t = $('.item_employee:visible');
            $(".counter").html("Toplam <strong>" + t.length + "</strong> kişi gösteriliyor");
        });
    </script>

    <script>
        $(function()
        {
            var size = $('.item_employee').length;
            var veriSayisi = 12;
            var sayi = 4;


            $("div.item_employee div:gt("+( sayi * veriSayisi) +")").hide();

            var toplamSayfa = Math.round(size / sayi);
            for (var i = 1;i <= toplamSayfa;i++)
            {
                $("#sayfalama").append("<a href='jasacript:void(0)'>" + i +"</a>")
            }
            $("#sayfalama a:first").addClass('aktif');

            $("#sayfalama a").click(function ()
            {
                $("#sayfalama a").removeClass('aktif');

                $(this).addClass('aktif');
                $("div.item_employee div").hide();
                var indis = $(this).index() +1;
                var deger = veriSayisi * sayi;
                var gt = deger * indis;
                for (var i = gt - deger ; i < gt; i++ )
                {
                    $("div.item_employee div:eq(" + i +  ")").show();
                }
            });




        });
    </script>

@endsection

