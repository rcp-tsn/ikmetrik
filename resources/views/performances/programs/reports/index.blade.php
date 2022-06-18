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
        #label
        {
            font-weight: bold;
            font-size: 15px;
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
                                    <span class="d-block text-dark font-weight-bolder">Performans Raporları</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>
                            <div class="form-group" style="text-align: -webkit-right;margin-right: 20px; padding-top: 20px">
                                <a href="{{ URL::previous() }}">
                                    <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                        <i class="la la-backspace"></i>Geri</div>
                                </a>
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

                            @foreach($performances as $performance)

                            <div class="card card-custom gutter-b card-stretch item_employee">
                                <!--begin::Body-->
                                <div class="card-body">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Pic-->

                                        <!--end::Pic-->
                                        <!--begin::Info-->

                                        <!--end::Info-->
                                        <table class="table table-responsive">
                                            <tr>
                                                <thead>
                                                <th>Performans Bilgisi
                                                </th>
                                                <th>Dönem Performans Bilgileri</th>
                                                <th>Bireysel Rapor</th>
                                                <th>Toplu Rapor</th>
                                                <th>Eğitim İhityaç Analizi</th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column mr-auto">
                                                            <!--begin: Title-->
                                                            <a href="#" class="card-title text-hover-primary font-weight-bolder font-size-h5 text-dark mb-1">{{$performance->name}}</a>

                                                            <!--end::Title-->
                                                        </div></td>
                                                    <td><button type="button" data-id="{{$performance->id}}" class="btn btn-primary perfor"><span class="navi-icon">
																				<i class="flaticon2-user"></i>
																			</span>
                                                                <span class="navi-text">Performans Bilgileri</span></button> </td>
                                                    <td><a href="{{route('performance_user_report', createHashId($performance->id)) }}" class="navi-link"> <button class="btn btn-primary"><span class="navi-icon">
																				<i class="flaticon2-user"></i>
																			</span>
                                                                <span class="navi-text">Bireysel Rapor</span></button>   </a></td>
                                                    <td><button class="btn btn-primary">
                                                            <a href="{{route('performance_rapor_document',createHashId($performance->id))}}" class="navi-link">
																			<span class="navi-icon">
																				<i class="flaticon2-user-1"></i>
																			</span>
                                                                <span class="navi-text" style="color: #ffffff">Toplu Rapor</span>
                                                            </a>
                                                        </button></td>
                                                    <td>

                                                        <a href="{{route('education_report', ['id' => createHashId($performance->id)]) }}" class="navi-link"> <button class="btn btn-primary"><span class="navi-icon">
																				<i class="flaticon2-user"></i>
																			</span>
                                                                <span class="navi-text">Eğitim İhtiyaç Analiz</span></button>   </a>

                                                        <a href="{{route('evalation_report_index', ['id' => createHashId($performance->id)]) }}" class="navi-link m-t-5"> <button class="btn btn-primary m-t-5"><span class="navi-icon">
																				<i class="flaticon2-user"></i>
																			</span>
                                                                <span class="navi-text">Değerlendirme Sonuçları</span></button>   </a>
                                                    </td>
                                                    <td>


                                                    </td>
                                                </tr>
                                                </tbody>
                                            </tr>
                                        </table>
                                        <!--begin::Toolbar-->
                                        <!--end::Toolbar-->
                                    </div>
                                    <!--end::Section-->
                                    <!--begin::Content-->
                                    <div class="performans{{$performance->id}} performans">
                                    <div class="d-flex flex-wrap mt-14">
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="d-block  mb-4" id="label">Başlangıç Tarih</span>
                                            <span class="btn btn-light-primary btn-sm font-weight-bold btn-upper btn-text">{{$performance->start_date->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="d-block  mb-4" id="label">Bitiş Tarih</span>
                                            <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text">{{$performance->finish_date->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="d-block  mb-4" id="label"> Kalan Süre</span>
                                            <span class="btn btn-light-danger btn-sm font-weight-bold btn-upper btn-text">{{$backDay[$performance->id]}}</span>
                                        </div>
                                        <!--begin::Progress-->
                                        <div class="flex-row-fluid mb-7">
                                            <span class="d-block  mb-4" id="label">Tamamlanma Durumu</span>
                                            <div class="d-flex align-items-center pt-2">
                                                <div class="progress progress-xs mt-2 mb-2 w-100" style="height: 30px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{$sonuc}}%;" aria-valuenow="{{$sonuc}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="ml-3 font-weight-bolder">{{number_format($sonuc,2,',','.')}}%</span>
                                            </div>
                                        </div>
                                        <!--end::Progress-->
                                    </div>
                                    <!--end::Content-->
                                    <!--begin::Blog-->
                                    <div class="d-flex flex-wrap">
                                        <!--begin: Item-->
                                        <div class="mr-12 d-flex flex-column mb-7">
                                            <span class="font-weight-bolder mb-4" id="label">Katılımcı Sayısı</span>
                                            <span class="font-weight-bolder font-size-h5 pt-1">
														<span class="font-weight-bold text-dark-50"></span>{{$performance->applicantCount($performance->id)}}</span>
                                        </div>
                                        <!--end::Item-->

                                        <!--begin::Item-->

                                    </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Blog-->
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer d-flex align-items-center">
                                    <div class="d-flex">
                                        <div class="d-flex align-items-center mr-7">
														<span class="svg-icon svg-icon-gray-500">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Text/Bullet-list.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<rect x="0" y="0" width="24" height="24"></rect>
																	<path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"></path>
																	<path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"></path>
																</g>
															</svg>
                                                            <!--end::Svg Icon-->
														</span>
                                            <a href="#" class="font-weight-bolder text-primary ml-2" id="label">{{$toplam}} Değerlendirme</a>
                                        </div>

                                    </div>
                                </div>
                                <!--end::Footer-->
                            </div>
                            @endforeach
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
                var sayi = 8;
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
            var sayi = 2;


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
            $(".performans").hide();
    </script>
    <script>
        $(".perfor").click(function ()
        {
           var id = $(this).data('id');
           $(".performans"+id).toggle();
        });
    </script>
@endpush

