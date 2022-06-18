@extends('layouts.app')
@section('content')
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

    <div class="subheader py-2 py-lg-4  subheader-transparent" id="kt_subheader">

        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

            <!--begin::Info-->

            <div class="d-flex align-items-center flex-wrap mr-1">

                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline mr-5">

                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-2 mr-5">
                    </h5>

                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                            </a>
                        </li>
                    </ul>

                    <!--end::Breadcrumb-->
                </div>

                <!--end::Page Heading-->
            </div>

            <!--end::Info-->

            <!--begin::Toolbar-->

        </div>
    </div>
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

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Performans Program Katılımcıları</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
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
                                        <label for="search">Program Arama : </label>
                                        <input type="text" name="search" id="search" class="form-control search">
                                    </div>
                                </div>
                            </div>

                       <div class="row">
                           @foreach($employees as $employee)
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
                                                   <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">{{$employee->full_name}}</a>
                                               </div>
                                               <!--end::Title-->
                                           </div>
                                           <!--end::Title-->
                                       </div>
                                       <!--end::User-->
                                       <!--begin::Desc-->
                                       <p class="mb-7">Personellerin
                                           <a href="#" class="text-primary pr-1"></a>Bireysel Raporlarını Alabilirisiniz</p>
                                       <!--end::Desc-->
                                       <!--begin::Info-->
                                       <div class="mb-7">
                                           <div class="d-flex justify-content-between align-items-center">
                                               <span class="text-dark-75 font-weight-bolder mr-2">Email:</span>
                                               <a href="#" class="text-muted text-hover-primary">{{$employee->email}}</a>
                                           </div>
                                           <div class="d-flex justify-content-between align-items-cente my-1">
                                               <span class="text-dark-75 font-weight-bolder mr-2">Phone:</span>
                                               <a href="#" class="text-muted text-hover-primary">{{$employee->phone}}</a>
                                           </div>
                                           <div class="d-flex justify-content-between align-items-center">
                                               <span class="text-dark-75 font-weight-bolder mr-2">Adres</span>
                                               <span class="text-muted font-weight-bold">{{$employee->address}}</span>
                                           </div>
                                       </div>
                                       <!--end::Info-->
                                       <a href="{{route('user_performance_rapor_document',[ 'employee_id' => $employee->id , 'program_id' => $id])}}" class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4">Rapor Al</a>
                                       <a href="#" data-id="{{$employee->id}}"   class="btn btn-block btn-sm btn-light-success font-weight-bolder reportButton text-uppercase py-4 mt-4">Rapor Göster</a>
                                   </div>
                                   <!--end::Body-->
                               </div>
                               <!--end::Card-->
                           </div>
                           @endforeach
                       </div>
                        </div>
                        <div id="sayfalama" style="text-align: -webkit-right">

                        </div>
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

    <!-- Employee Modal RAPOR show Başlangıç -->
    <div class="reportModal">

    </div>

    <!-- Bitiş -->



    <!--end::Entry-->
@stop

@push('scripts')
    <script>
        $('#search').keyup(function () {

            var tg = $('.item_employee').length;
            $("div.item_employee div:gt(" + tg +  ")").show();

            if ($('#search').val().length < 1) {
                tg = $('.item_employee');
                $("div.item_employee div:gt(" + tg +  ")").hide();
                var veriSayisi = 13;
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

            var veriSayisi = 13;
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



    <script>
        $(".reportButton").click(function ()
        {
            var employee_id =  $(this).data('id');
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/performance-employee-report',
                data: {
                    employee_id:employee_id,
                    performance_id:{{$id}}
                },
                success: function (data) {
                    $(".reportModal").html(data);
                    $("#Report"+employee_id).modal('show');
                },
            });
        });
    </script>

@endpush

