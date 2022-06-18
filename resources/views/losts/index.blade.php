@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row mt-0 mt-lg-12">
                <div class="col-xl-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">E-BildirgeV2 VE İŞVEREN SİSTEMİ TARAMASI</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row method-incentive">
                                <div class="col-lg-12">
                                    <div class="card card-body border-top-1 border-top-pink">
                                        <div class="text-center">
                                            <h6 class="m-0 font-weight-semibold">Geçmiş Kayıp Kaçaklar Hesapla </h6>
                                            <h6 class="m-0 font-weight-semibold font-size-14 text-danger"> Tüm Kanunlarına Bakılacak</h6>
                                            <p class="text-muted mb-3">SGK Ebildirge V2 sistemi üzerinden bildirgeleri çeker.</p>
                                            <button type="button" onclick="stepManager.work('v2_tahakkuk_date');" class="btn btn-primary start-incentive"><b><i class="icon-collaboration"></i></b> Devam Et</button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <br/>

                            <br/>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-body border-top-1 border-top-pink justify-content-center align-items-center">
                                        <div class="col-lg-6">
                                            <div class="progress" style="height: 4rem;font-size: 1.5rem;">
                                                <div class="progress-bar progress-bar-striped bg-warning" style="width: 10%">
                                                    <span id="step-info">0%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="timeline timeline-5 mt-3 list-feed" style="overflow-y: scroll;max-height: 100px;">
                                                <div class="timeline-item align-items-start list-feed-item">
                                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">2</div>
                                                    <div class="timeline-badge">
                                                        <i class="fa fa-genderless text-success icon-xxl"></i>
                                                    </div>
                                                    <div class="timeline-content text-dark-50">
                                                        Seçim bekleniyor.
                                                    </div>
                                                </div>
                                                <div class="timeline-item align-items-start list-feed-item">
                                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">1</div>
                                                    <div class="timeline-badge">
                                                        <i class="fa fa-genderless text-success icon-xxl"></i>
                                                    </div>
                                                    <div class="timeline-content text-dark-50">
                                                        Sistem hazır.
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('modal')
    <div id="loginModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <form method="POST" action="#" id="loginModalForm" class="form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <img id="captchaImg" src="/assets/media/logos/logo.png"/>
                        <div class="form-group">
                            <label>Güvenlik Kodu</label>
                            {!! Form::text("captcha",null,['class'=>'form-control','placeholder'=>'Güvenlik Kodu']) !!}
                        </div>
                        <button type="submit" name="submitBtn" id="submitBtn" class="btn btn-success font-weight-bold btn-square">GİRİŞ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('modal')
    @if(session()->has('dates2'))
        @php $dates2 = session()->get('dates2'); @endphp
    <div id="loginModalDate" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                            <div class="form-group">
                                <label for="">Tarama Yapmak İstediğiniz Ayları Seçiniz</label></div>
                            <form action="#" class="form" id="loginModalFormDate" method="POST">
                             @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <select name="one_date" class="form-control" id="">
                                    <option value="-1">Seçiniz</option>
                                    @foreach($dates2 as $date)
                                        <?php echo $date; ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="two_date" class="form-control" id="">
                                    <option value="-1">Seçiniz</option>
                                    @foreach($dates2 as $date)
                                        <?php echo $date; ?>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-5">
                            <input type="submit" id="dateBtn" name="dateBtn" class="form-control button" value="BAŞLAT">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endif
    @endpush
@push('scripts')
    <script>
        var steps = {
            'v2_tahakkuk_date': {
                url: '{!! route('declaration.v2.TahakkukDate') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'v2_tahakkuk': {
                url: '{!! route('declaration.v2.LostTahakkuk') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'pdf_download': {
                url: '{!! route('declaration.v2.LostPdfDownload') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'pdf_parse': {
                url: '{!! route('declaration.v2.LostPdfParse') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'v3_newRequest': {
                url: '{!! route('declaration.v3.LeakNewRequest') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },
            'v3_6111': {
                url: '{!! route('declaration.lost.6111') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },
            'v3_26': {
                url: '{!! route('declaration.lost.26') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },
            'v3_7103': {
                url: '{!! route('declaration.lost.7103') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },


        };

        var stepManager = {
            currentStep: 'v2_tahakkuk_date',
            work: function (step) {
                axios.get(steps[step].url).then(function (response) {
                    if (response.data) {
                        if (response.data.code == 'LOGIN_FAIL') {
                            console.log(steps[step].url+"|data key|"+response.data.key + "|step|"+ step);
                            //stepManager.currentStep = step;
                            //stepManager.work(response.data.step);
                            loginModal(steps[step].loginurl, response.data.key, step);
                        }

                        else {
                            console.log('response.data.step');
                            console.log(response.data.step)
                            if (response.data.code == 'DATE')
                            {

                                var targetUrl2 = 'http://tesvik3.ikmetrik.com/declaration/Tahakkuk_Lost';
                                loginModalDate(targetUrl2)

                            }
                            if (response.data.code == 'SUCCESS') {
                                console.log(response.data.step);
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html(response.data.progress + "%");
                                $(".progress-bar").css('width', response.data.progress + '%');
                                stepManager.work(response.data.step);
                                stepManager.currentStep = response.data.step;
                            } else if (response.data.code == 'TIMEOUT') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html(response.data.progress + "%");
                                $(".progress-bar").css('width', response.data.progress + '%');
                                stepManager.work(response.data.step);
                                stepManager.currentStep = response.data.step;
                            } else if (response.data.code == 'FINISH') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html(response.data.progress + "%");
                                $(".progress-bar").css('width', response.data.progress + '%');
                                returnList(response.data.url);
                            } else if (response.data.code == 'ERROR') {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html("100%");
                                $(".progress-bar").css('width', '100%');
                            } else {
                                $(".list-feed").prepend('<div class="timeline-item align-items-start list-feed-item"><div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-right pr-3">' + response.data.progress + '</div><div class="timeline-badge"><i class="fa fa-genderless text-success icon-xxl"></i></div><div class="timeline-content text-dark-50">' + response.data.message + '</div></div>');
                                $("#step-info").html("İşlem Durduruldu!");
                            }
                        }
                    }
                }).catch(function (error) {
                    $("#step-info").html("İşlem Durduruldu!");
                    console.log('203');
                    console.log(error);
                });
            },
            nextStep: function () {
            },
            advance: function () {
                console.log('210');
                console.log('de:'+stepManager.currentStep);
                stepManager.work(stepManager.currentStep);

            }
        };


        function returnList(url) {
            $(".card-body").html("Yönlendiriliyorsunuz...");
            setTimeout(function () {
                window.location.assign(url);
            }, 3000);
        }

        function loginModal(targetUrl, key) {
            var captchaKey = returnCaptchaUrl(key);
            console.log('dd:'+captchaKey);
            if (captchaKey === 'undefined' ||captchaKey === undefined || captchaKey === null) {
                $("#step-info").html("Captcha alınamadı");
            } else {
                $("[name*='captcha']").val("");
                $('#captchaImg').attr('src', captchaKey);
                $('#loginModal').modal('show');
                $('#loginModalForm').attr('action', targetUrl);
            }

        }

        function loginModalDate(targetUrl2) {
            console.log(targetUrl2)
                $('#loginModalDate').modal('show');
                $('#loginModalFormDate').attr('action', targetUrl2);
        }


        $(document).ready(function () {
            $("#loginModalForm").submit(function (e) {
                e.preventDefault();
                $('#loginModal').modal('hide'); // Modal Hidden
                var form = $(this);
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (response) {
                        if (response.code == 'LOGIN_OK') {
                            stepManager.advance();
                        } else {
                            stepManager.advance();
                            $("#step-info").html("Hatalı Giriş!");
                        }
                    },

                    error: function (error) {
                        $("#step-info").html("Hatalı Giriş!");
                        console.log(error);
                    }
                });
            });
        });

        $(document).ready(function () {
            $("#loginModalFormDate").submit(function (e) {
                e.preventDefault();
                $('#loginModalDate').modal('hide'); // Modal Hidden
                var form = $(this);
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function (response) {
                        if (response.code == 'SUCCESS') {
                            stepManager.work(response.step);
                        } else {
                            stepManager.advance();
                            $("#step-info").html("Hatalı Giriş!");
                        }
                    },

                    error: function (error) {
                        $("#step-info").html("Hatalı Giriş!");
                        console.log(error);
                    }
                });
            });
        });

        function returnCaptchaUrl(key) {
            if (key === 'undefined' ||key === undefined || key === null) {
                return null;
            } else {
                return '{!! route('captcha') !!}' + '/' + key;
            }

        }

        $(".start-incentive").click(function () {
            $('.method-incentive').hide(500);
        });
        var uploadURL = "{{ route('declarations.incentives.excel_upload') }}";
    </script>

@endpush
