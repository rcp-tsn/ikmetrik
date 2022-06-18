@extends('layouts.app')
@section('content')


    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">

        <!--begin::Container-->
        <div class=" container-fluid ">
            <!--begin::Dashboard-->
            <br/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center border-bottom border-white py-10">
                        <h3 class="h4 text-dark mb-5 ml-5">Sn. {{ Auth::user()->name }}<small> Dijital Performans Değerlendirme  Yönetim Ekranına Hoşgeldiniz.</small></h3>
                    </div>
                    <ul class="nav nav-success nav-pills" id="myTab3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab-metrik" data-toggle="tab" href="#tab-metrik">
                                    <span class="nav-icon">
                                        <i class="flaticon2-pie-chart"></i>
                                    </span>
                                <span class="nav-text">PERFORMANS DEĞERLENDİRME MODÜLÜMÜZ</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-5" id="myTabContent3">
                        <div class="tab-pane fade active show" id="tab-metrik" role="tabpanel" aria-labelledby="home-tab-metrik">
                            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Performance'))
                                <div class="row">
                                    <div class="col-xl-12">
                                        @include('partials.alerts.error')
                                        <div class="separator separator-solid separator-border-2 separator-warning"></div>
                                        <div class="d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url('/assets/media/bg/bg-search.jpg')">
                                            <div class="container-fluid ik-metrik-search-area" >
                                                <div class="d-flex align-items-stretch text-center flex-column py-15">
                                                    <!--begin::Heading-->
                                                    <h1 class="text-dark font-weight-bolder mb-12">DİJİTAL PERFORMANS DEĞERLENDİRME SİSTEMİ</h1>
                                                    <!--end::Heading-->
                                                    <!--begin::Form-->
                                                    <form class="d-flex position-relative w-75 px-lg-20 m-auto">



                                                        <div class="form-group row">
                                                            <div class="input-group">
                                                                <div class="typeahead">
                                                                    <input class="form-control" id="kt_typeahead_5" type="text" dir="ltr" placeholder="Aranılacak metrik için birkaç kelime yazmanız yeterli..." />
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row ik-metrik-area">

                                    <div class="col-xl-4">
                                        <!--begin::Engage Widget 5-->
                                        <div class="card card-custom card-stretch gutter-b">
                                            <div class="card-body d-flex p-0">
                                                <div class="flex-grow-1 bg-info p-12 pb-40 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + -0.5rem) bottom; background-size: 42% auto;background-image: url(/assets/media/svg/kpi/bireysel.png)">
                                                    <h3 class="text-inverse-info pb-5 font-weight-bolder">BİREYSEL PERFORMANS DEĞERLENDİRME</h3>
                                                    <p class="text-inverse-info pt-10 pb-5 font-size-h3 font-weight-bolder line-height-lg">İnsan kaynakları süreçlerinde
                                                        <br>hedef belirleme
                                                        <br>ve analiz metrikleri</p>
                                                    <a href="{{ route('questions.index') }}" class="btn btn-success font-weight-bold py-2 px-6">PROGRAMLARI GÖSTER </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Engage Widget 5-->
                                    </div>
                                    <div class="col-xl-4">
                                        <!--begin::Engage Widget 4-->
                                        <div class="card card-custom card-stretch gutter-b">
                                            <div class="card-body d-flex p-0">
                                                <div class="flex-grow-1 bg-warning p-12 pb-40 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + -0.5rem) bottom; background-size: 42% auto; background-image: url(/assets/media/svg/kpi/isletme.png)">
                                                    <h3 class="text-inverse-warning pb-5 font-weight-bolder">PERFORMANS HEDEF YÖNETİMİ</h3>
                                                    <p class="text-inverse-warning pt-10 pb-5 font-size-h3 font-weight-bolder line-height-lg">İşletme performans değerlendirme süreçlerinde
                                                        <br>temel girdi
                                                        <br>metrikleri</p>
                                                    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Performance') || Auth::user()->hasRole('metric'))
                                                        <a href="{{ route('question_evalation', [ 'type' => 'target' , 'id' => createHashId(30),'page' => 1 ]) }}" class="btn btn-success font-weight-bold py-2 px-6">Hedefler</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Engage Widget 4-->
                                    </div>
                                    <div class="col-xl-4">
                                        <!--begin::Engage Widget 6-->
                                        <div class="card card-custom card-stretch gutter-b">
                                            <div class="card-body d-flex p-0">
                                                <div class="flex-grow-1 bg-danger p-12 pb-40 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: 52% auto; background-image: url(/assets/media/svg/kpi/rapor.png)">
                                                    <h3 class="text-inverse-info pb-5 font-weight-bolder">PERFORMANS RAPORLAMA VE DÖKÜMANTASYON</h3>
                                                    <p class="text-inverse-danger pt-10 pb-5 font-size-h3 font-weight-bolder line-height-lg">Performans değerlendirme distemi raporlama
                                                        <br>ve döküman
                                                        <br>yönetimi</p>
                                                    <a href="{{ route('performance_reports') }}" class="btn btn-warning font-weight-bold py-2 px-6">RAPORLAMA</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Engage Widget 6-->
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-custom alert-notice alert-light-warning fade show mb-5" role="alert">
                                    <div class="alert-icon ">
                                        <i class="flaticon-warning font-size-lg text-danger"></i>
                                    </div>
                                    <div class="alert-text text-dark font-size-h3">Performans Değerlendirme  modülümüz, paketinizde bulunmamaktadır. Metrik modülümüz hakkında bilgi almak için lütfen bizimle irtibata geçiniz.</div>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="tab-tesvik" role="tabpanel" aria-labelledby="home-tab-tesvik">
                            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Teşvik') || Auth::user()->modulePermit(1))
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="separator separator-solid separator-border-2 separator-warning"></div>
                                        <div class="d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url('/assets/media/bg/bg-search.jpg')">
                                            <div class="container-fluid">
                                                <div class="d-flex align-items-stretch text-center flex-column py-15">
                                                    <!--begin::Heading-->
                                                    <h1 class="text-dark font-weight-bolder mb-12">Hangi Aya Ait Teşvikleri Görüntülemek İstemiştiniz?</h1>
                                                    <!--end::Heading-->
                                                    <!--begin::Form-->
                                                    <form class="d-flex position-relative w-75 px-lg-20 m-auto">



                                                        <div class="form-group row">
                                                            <div class="input-group">
                                                                <div class="typeahead">
                                                                    <input class="form-control" id="kt_typeahead_6" type="text"  dir="ltr" placeholder="Aranılacak ay için birkaç kelime yazmanız yeterli..." />
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </form>
                                                    <!--end::Form-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-xl-4">
                                        <!--begin::Engage Widget 4-->
                                        <div class="card card-custom card-stretch gutter-b">
                                            <div class="card-body d-flex p-0">
                                                <div class="flex-grow-1 bg-warning p-12 pb-40 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + -0.5rem) bottom; background-size: 54% auto; background-image: url(/assets/media/svg/kpi/t1.png)">
                                                    <h3 class="text-inverse-warning pb-5 font-weight-bolder">Teşvik Raporları</h3>
                                                    <p class="text-inverse-warning pt-10 pb-5 font-size-h3 font-weight-bolder line-height-lg">Ay bazında şube
                                                        <br>teşvik <BR/>raporları</p>>
                                                    <a href="/declarations/incentives/pdf-incentive-reports" class="btn btn-success font-weight-bold py-2 px-6">RAPORLARI LİSTELE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Engage Widget 4-->
                                    </div>
                                    <div class="col-xl-4">
                                        <!--begin::Engage Widget 5-->
                                        <div class="card card-custom card-stretch gutter-b">
                                            <div class="card-body d-flex p-0">
                                                <div class="flex-grow-1 bg-info p-12 pb-40 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + -0.5rem) bottom; background-size: 62% auto;background-image: url(/assets/media/svg/kpi/t2.png)">
                                                    <h3 class="text-inverse-info pb-5 font-weight-bolder">Firma Teşvik Raporları</h3>
                                                    <p class="text-inverse-info pt-10 pb-5 font-size-h3 font-weight-bolder line-height-lg">Ay bazında toplu
                                                        <br>firma teşvik <BR/>raporları</p>
                                                    <a href="/declarations/incentives/all_gain_incentives" class="btn btn-success font-weight-bold py-2 px-6">RAPORU GÖRÜNTÜLE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Engage Widget 5-->
                                    </div>
                                    <div class="col-xl-4">
                                        <!--begin::Engage Widget 6-->
                                        <div class="card card-custom card-stretch gutter-b">
                                            <div class="card-body d-flex p-0">
                                                <div class="flex-grow-1 bg-danger p-12 pb-40 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: 65% auto; background-image: url(/assets/media/svg/kpi/t3.png)">
                                                    <h3 class="text-inverse-info pb-5 font-weight-bolder">Şube Seçimi</h3>
                                                    <p class="text-inverse-danger pt-10 pb-5 font-size-h3 font-weight-bolder line-height-lg">Firmanınız altında
                                                        <br>yer alan alt şubelerinizin
                                                        <br>bilgileri</p>
                                                    <a href="/sgk_company_select" class="btn btn-warning font-weight-bold py-2 px-6">ŞUBE GÖRÜNTÜLE</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Engage Widget 6-->
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-custom alert-notice alert-light-warning fade show mb-5" role="alert">
                                    <div class="alert-icon ">
                                        <i class="flaticon-warning font-size-lg text-danger"></i>
                                    </div>
                                    <div class="alert-text text-dark font-size-h3"> Teşvik modülümüz, paketinizde bulunmamaktadır. Teşvik modülümüz hakkında bilgi almak için lütfen bizimle irtibata geçiniz.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::Row-->





            <!--end::Row-->
            <!--end::Dashboard-->
        </div>

        <!--end::Container-->
    </div>

    <!--end::Entry-->
@stop
@push('modal')
    <div class="modal fade" id="staticWelcomeModal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">İK Metrik Sistemine Hoşgeldiniz!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">Merhaba <strong>{{ Auth::user()->name }}</strong>;<br/>
                    Tüm sayfalarımızın sol alt köşesinde yer alan <span class="svg-icon svg-icon-xxl">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <circle fill="#000000" opacity="0.3" cx="12" cy="9" r="8"/>
                        <path d="M14.5297296,11 L9.46184488,11 L11.9758349,17.4645458 L14.5297296,11 Z M10.5679953,19.3624463 L6.53815512,9 L17.4702704,9 L13.3744964,19.3674279 L11.9759405,18.814912 L10.5679953,19.3624463 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        <path d="M10,22 L14,22 L14,22 C14,23.1045695 13.1045695,24 12,24 L12,24 C10.8954305,24 10,23.1045695 10,22 Z" fill="#000000" opacity="0.3"/>
                        <path d="M9,20 C8.44771525,20 8,19.5522847 8,19 C8,18.4477153 8.44771525,18 9,18 C8.44771525,18 8,17.5522847 8,17 C8,16.4477153 8.44771525,16 9,16 L15,16 C15.5522847,16 16,16.4477153 16,17 C16,17.5522847 15.5522847,18 15,18 C15.5522847,18 16,18.4477153 16,19 C16,19.5522847 15.5522847,20 15,20 C15.5522847,20 16,20.4477153 16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 C8,20.4477153 8.44771525,20 9,20 Z" fill="#000000"/>
                    </g>
                </svg>
            </span> ikonuna tıklayarak sayfa işlevleri hakkında bilgi alabilirsiniz.
                    <br/>
                    <br/>
                    <p><img src="/assets/images/popup_image_1.png"><br/></p>

                    <p><img src="/assets/images/popup_image_2.jpg"></p>
                    <p>32 Ayrı İK Metriğine Ait Raporlama: İnsan kaynakları süreçlerinde gerçekleştirilen ölçme ve değerlendirme faaliyetleri işletmelerin hedeﬂerine ulaşmasında kritik rol oynuyor.</p>
                    <p><img src="/assets/images/popup_image_3.jpg"></p>
                    <div class="modal-footer">
                        <div class="checkbox-inline">
                            <label class="checkbox checkbox-square">
                                <input type="checkbox"  value="1" name="dontShowAgain" id="dontShowAgain" />
                                <span></span>Bir daha gösterme</label>
                        </div>
                        <button type="button" class="btn btn-light-primary font-weight-bold" id="btnCloseModal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>
        @endpush
        @push('scripts')
            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Teşvik') || Auth::user()->modulePermit(1))
                <script>

                    // Class definition
                    var KTTypeaheadTesvik = function() {

                        var demo_tesvik = function() {
                            var tesvik_aylari = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('metrics'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                prefetch: '/assets/tmp/aylar.json'
                            });



                            $('#kt_typeahead_6').typeahead(
                                {
                                    highlight: true,

                                },
                                {
                                    name: 'tesvik-aylar',
                                    display: 'metrics',
                                    source: tesvik_aylari,
                                    templates: {
                                        header: '<h3 class="league-name">AYLAR</h3>'
                                    }
                                }).on('typeahead:selected typeahead:autocompleted', function(e, datum) {
                                $.getJSON("/get-ajax-incentives/"+ datum.metrics, function(jsonData)
                                {
                                    if (jsonData.success) {
                                        location.href = '/declarations/incentives/gain_incentives?date=' + jsonData.tesvik;
                                    }
                                });
                            });

                        }

                        return {
                            // public functions
                            init: function() {
                                demo_tesvik();
                            }
                        };
                    }();

                    jQuery(document).ready(function() {
                        KTTypeaheadTesvik.init();
                    });
                </script>

            @endif

            @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Metrik') || Auth::user()->modulePermit(2))

                <script>

                    // Class definition
                    var KTTypeaheadMetrik = function() {

                        var demo5 = function() {
                            var ikMaliyet = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('metrics'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                prefetch: '/assets/tmp/1.json'
                            });

                            var ikOperasyonel = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('metrics'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                prefetch: '/assets/tmp/2.json'
                            });

                            var ikIsletme = new Bloodhound({
                                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('metrics'),
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                prefetch: '/assets/tmp/3.json',
                            });


                            $('#kt_typeahead_5').typeahead({
                                    highlight: true,

                                },
                                {
                                    name: 'ik-maliyet',
                                    display: 'metrics',
                                    source: ikMaliyet,
                                    templates: {
                                        header: '<h3 class="league-name">İK Maliyet Metrikleri</h3>'
                                    }
                                },
                                {
                                    name: 'ik-operasyonel',
                                    display: 'metrics',
                                    source: ikOperasyonel,
                                    templates: {
                                        header: '<h3 class="league-name">İK Operasyonel Metrikleri</h3>'
                                    }
                                },
                                {
                                    name: 'ik-isletme',
                                    display: 'metrics',
                                    source: ikIsletme,
                                    templates: {
                                        header: '<h3 class="league-name">İK İşletme Metrikleri</h3>'
                                    }
                                }).on('typeahead:selected typeahead:autocompleted', function(e, datum) {
                                console.log(datum.metrics);
                                $.getJSON("/get-ajax-metrics/"+ datum.metrics, function(jsonData){
                                    if (jsonData.success) {
                                        location.href = 'sub-metrics/' + jsonData.metric;
                                    }
                                });
                            });

                        }

                        return {
                            // public functions
                            init: function() {
                                demo5();
                            }
                        };
                    }();

                    jQuery(document).ready(function() {
                        KTTypeaheadMetrik.init();
                    });

                </script>
            @endif

            <script type="text/javascript">
                $(window).on('load',function(){

                    if (typeof $.cookie('dont_show_again') === 'undefined'){
                        //no cookie
                        $('#staticWelcomeModal').modal('show');
                    } else {
                        //have cookie

                    }



                });
                function cookieBtn()
                {
                    if (typeof $.cookie('dont_show_again') === 'undefined'){
                        //no cookie
                        $('#staticWelcomeModal').modal('show');
                    } else {
                        //have cookie
                        $('#staticWelcomeModal').modal('hide');
                    }
                }
                $(document).ready(function () {
                    cookieBtn();
                });
                $(function () {
                    $("#btnCloseModal").click(function () {
                        var ckbox = $('#dontShowAgain');

                        if (ckbox.is(':checked')) {
                            var cookURL =  $.cookie('dont_show_again', 'deneme', { expires: 30 });
                        } else {
                            console.log($('#dontShowAgain').val() + 'unchecked');
                        }
                        cookieBtn();


                        $('#staticWelcomeModal').addClass('bounceOutRight');
                        $("#staticWelcomeModal").modal("hide");
                    });
                });
            </script>

            <script>
                'use strict';
                $(document).ready(function () {

                    $(document).on('click', 'a.page-tour', function () {
                        var enjoyhint_instance = new EnjoyHint({});

                        enjoyhint_instance.set([
                            {
                                'next .profile-area': 'Profil sekmesi. Şifre değişikliği ve güvenli çıkış işlemlerini bu bölümden yapabilirsiniz.',
                            },
                            {
                                'next .aside-left': 'Ana menülerimiz. Sistem içerisindeki tüm bölümlere buradan erişebilirsiniz.',
                            },
                            {
                                'next .company-select-area': 'Bilgilerini görüntülemek istediğiniz firmayı burdan seçebilirsiniz. Seçili firmayı değiştirebilirsiniz. Aktif Firma, ana firmanız. Seçili Firma bölümü ise şuan bilgilerini görüntülediğiniz firmanızı ifade eder.',
                            },
                            {
                                'next .faq-area': 'Sık sorulan soruları sizin için derledik. Merak ettikleriniz için bu sayfayı ziyaret edebilirsiniz.',
                            },
                            {
                                'next .send-message-area': 'Bizimle İletişime geçmek için destek formumuz. Tüm soru, sorun ve istekleriniz için bizimle bu form aracılığıyla iletişim kurabilirsiniz.',
                            },
                            {
                                'next .ik-metrik-area': 'Metriklerimiz: İK Operasyonel, İK Maliyet  ve İK İşletme Metriklerimize bu bölümlerdeki "Metrikleri Görüntüle" butonuna tıklayarak ulaşabilirsiniz.',
                            },
                            {
                                'next .ik-metrik-search-area': 'Metriklerimize bu bölümdeki arama kutusu ile de ulaşanbilirsiniz. ',
                            }
                        ]);
                        enjoyhint_instance.run();
                    });

                });
            </script>
@endpush

