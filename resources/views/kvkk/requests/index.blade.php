@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Talep şikayet sayfası'])
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

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Kvkk Talep Şikayetler</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>
                    @include('partials.alerts.error')
                    <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="kt_datatable">
                                    <thead>
                                    <tr>
                                        <th>Adı Soyadı</th>
                                        <th>Firma ile İlişkisi</th>
                                        <th>Telefon</th>
                                        <th>Adres</th>
                                        <th>Başvuru Formu</th>
                                        @if(Auth::user()->hasAnyRole('Admin','kvkk'))
                                            <th>İşlemler</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>{{$request->name}}</td>
                                            <td>{{$request->company_contact_type}}</td>
                                            <td>{{$request->phone}}</td>
                                            <td>{{$request->address}}</td>
                                            <td>
                                                <a href="/{{$request->file}}" download="talep_şikayet_formu.docx"><button class="btn btn-outline-primary btn-icon-left m-b-10">  <i class="fa fa-file-word-o"></i><span>Başvuruyu İndir</span></button></a>
                                            </td>
                                            <td>
                                                @if(Auth::user()->hasAnyRole('kvkk') and $request->status == 0 )
                                                    <button data-id="{{$request->name}}" data-employee="{{$request->employee_id}}" data-request="{{$request->id}}" aria-label="" class="btn btn-info btn-icon-left m-b-10 m-l-5 kisi_name " data-toggle="modal" data-target="#addNewAppModal2" type="button" ><i class="pg-icon"></i><span class="">Cevap Ver</span></button><br>
                                                @endif

                                                    @if($request->status==1)
                                                        <div>
                                                            <button class="btn btn-success companyRequest">Cevap Verilmiştir</button>
                                                            <a href="/{{$request->company_request_file}}" download=""><button data-id="{{$request->id}}"  class="btn btn-outline-primary btn-icon-left m-b-10">Cevabı İndir</button></a>
                                                        </div>
                                                    @else
                                                        <?php  $bugun  = $request->date;  ;
                                                        $gun_sonra = strtotime('+30 day',strtotime($bugun));
                                                        $gelecek = date("Y/m/d",$gun_sonra);
                                                        ?>


                                                        <div class="countDownParent">Kalan Zaman: <span data-date="{{$gelecek}}" class="countDownElement"></span></div>
                                                        <?php  $bugun  = $request->date;  ;
                                                        $gun_sonra = strtotime('+30 day',strtotime($bugun));
                                                        $gelecek = date("Y/m/d",$gun_sonra);
                                                        ?>
                                                        <script>
                                                            /*
                                                                Javascript ile ileri tarihli bir değere sahip elemanlara 'countDownOpen',
                                                                tarihi geçmiş değere sahip elemanlara ise 'countDownClose' sinifi eklenir.
                                                                'countDownOpen' sinifina sahip elemanlarda sayaç aktiftir.'countDownClose' sinifina sahip elemanlara herhangi bir islem yapilmaz
                                                                cunku tarihleri gecmistir. Sayfada 'countDownOpen' isimli sinifa sahip eleman yoksa sayaç proğramı pasif hale getirilir
                                                                yani 'setInterval' fonksiyonu silinir.
                                                            */
                                                            var dayText	= "Gün";
                                                            var hourText	= "Saat";
                                                            var minuteText	= "Dakika";
                                                            var secondText	= "Saniye";
                                                            var x = setInterval(function(){ //sayacı belirli aralıklarla yenile
                                                                if ($(".countDownClose").length == $(".countDownElement").length){ /*butun tarihler gecmis zamanli ise*/
                                                                    clearInterval(x);
                                                                    alert("İleri zamana ait bir tarih olmadığından dolayı bütün sayaçlar aktif değildir");
                                                                }

                                                                $(".countDownElement").each(function(){ //countDownElement class ismine sahip elemanları teker teker dön
                                                                    var thisElement = $(this); //elemanı elde et ve thisElement değişkenine ata. Bu değişken üzerinden elemana işlem yapılabilecek
                                                                    if (!$(thisElement).hasClass("countDownClose")){ //ilgili element 'countDownClose' isimli sinifa sahip degilse asagidaki kodlar calissin
                                                                        //thisElement.data("date") ile html elemanindaki 'data-date' ozelliginde tanimli tarihi elde ediyoruz. Bu tarih uzerinden islem yapiyoruz.
                                                                        var countDownDate = new Date(thisElement.data("date")).getTime(); //geri sayılacak ileri zamanki bir tarihi milisaniye cinsinden elde ediyoruz
                                                                        var now = new Date().getTime(); //şimdiki zamanı al
                                                                        var distance = countDownDate - now; //geri sayılacak tarih ile şimdiki tarih arasındaki zaman farkını al
                                                                        if (distance < 0) { //zaman farkı yok ise belirtilen zamanı geçti
                                                                            $(thisElement).html("Geri sayım yapılacak ileri bir tarih yoktur").removeClass("countDownOpen").addClass("countDownClose"); //
                                                                        }else { //zaman farkı var ise
                                                                            //aradaki zaman farkını gün,saat,dakika,saniye olarak böl
                                                                            var days = Math.floor(distance / (1000 * 60 * 60 * 24)),
                                                                                hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                                                                                minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                                                                                seconds = Math.floor((distance % (1000 * 60)) / 1000),
                                                                                days = (days?'<span>'+(days +' '+ dayText)+'</span>':''), //gun varsa gun degerini yaz
                                                                                hours = (hours?'<span>'+(hours +' '+ hourText)+'</span>':''), //saat varsa saat degerini yaz
                                                                                minutes = (minutes?'<span>'+(minutes +' '+ minuteText)+'</span>':''), //dakika varsa dakika degerini yaz
                                                                                seconds = (seconds?'<span>'+(seconds +' '+ secondText)+'</span>':''); //saniye varsa saniye degerini yaz
                                                                            thisElement.html(days +' '+ hours +' '+ minutes +' '+ seconds); //yazdır
                                                                        }
                                                                    }
                                                                });
                                                            }, 1000); //1 saniyede bir sayaç güncellenecek

                                                        </script>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


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
    <div class="modal fade stick-up" id="addNewAppModal2" tabindex="-1" role="dialog" aria-labelledby="addNewAppModal2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header clearfix ">
                    <button aria-label="" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-icon">X</i>
                    </button>
                    <h4 class="p-b-5"><span class="semi-bold">Cevap Yazın </span></h4>
                </div>
                <form  action="{{route('company_return')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <p class="small-text">Cevap Hazırlayın</p>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <input class="form-control" id="name" value="" readonly type="text">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>Açıklama</label>
                                    <input  type="text" class="form-control" id="notification"  name="notification">
                                </div>
                            </div>
                        </div>
                        @if(isset($request))
                            <input type="hidden" id="employee_id" name="id" value="">
                            <input type="hidden" id="request_id" name="request_id" value="">
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button aria-label="" id="add-app" type="submit" class="btn btn-primary  btn-cons">Cevap Oluştur</button>
                        <button aria-label="" type="button" class="btn btn-cons" data-dismiss="modal">Vazgeç</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <!--end::Entry-->
@stop

@push('scripts')
    <script>

        $(".kisi_name").click(function ()
        {
            var name = $(this).data('id');
            var employee_id = $(this).data('employee');
            var request_id = $(this).data('request');

            $("#employee_id").val(employee_id);
            $("#request_id").val(request_id);
            $("#name").val(name);
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




@endpush

