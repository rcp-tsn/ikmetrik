@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Teşvikleri Çek</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row method-incentive">
                                <div class="col-lg-12">
                                    <div class="card card-body border-top-1 border-top-pink">
                                        <div class="text-center">
                                            <h6 class="m-0 font-weight-semibold">Excel İle İlerle </h6>
                                            <h6 class="m-0 font-weight-semibold font-size-14 text-danger">{{ $option_laws_text }} Kanunlarına Bakılacak</h6>
                                            <p class="text-muted mb-3">Excel dosyalarını aşağıdaki butona tıklayarak toplu şekilde seçiniz.</p>

                                            <div class="form-group">
                                                <input type="file" id="file-input-ajax" name="excel" data-show-preview="false" multiple="multiple" data-fouc>
                                            </div>

                                            <div class="form-group">
                                                <button type="button" onclick="stepManager.work('excel_set');" class="btn btn-success start-incentive" id="start-incentive" style="display: none;">
                                                    <b><i class="icon-search4"></i></b> <span id="personel-count"></span> PERSONELİ TARAMAYA BAŞLA
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <div id="loginModal" class="modal fade" tabindex="99">
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
@push('scripts')
    <script>
        var steps = {
            'v2_bildirge': {
                url: '{!! route('declarations.v2.BildirgeCek') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'v2_bildirge_parse': {
                url: '{!! route('declarations.v2.BildirgeParse') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'excel_set': {
                url: '{!! route('declarations.incentives.excel_set') !!}',
                loginurl: false,
            },
            'v2_tahakkuk_list': {
                url: '{!! route('declarations.v2.TahakkukList') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'v2_pdf_download': {
                url: '{!! route('declarations.v2.PdfDownload') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'v2_pdf_parse': {
                url: '{!! route('declarations.v2.PdfParse') !!}',
                loginurl: '{!! route('declarations.loginv2.post') !!}',
            },
            'v3_new_request': {
                url: '{!! route('declarations.v3.NewRequest') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },

            'v3_3294': {
                url: '{!! route('declarations.v3.OldEncouragementSave_3294') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },

            'v3_6111': {
                url: '{!! route('declarations.v3.OldEncouragementSave_6111') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },

            'v3_26': {
                url: '{!! route('declarations.v3.OldEncouragementSave_26') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },
            'v3_31': {
                url: '{!! route('declarations.v3.OldEncouragementSave_31') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },
            'v3_7103': {
                url: '{!! route('declarations.v3.OldEncouragementSave_7103') !!}',
                loginurl: '{!! route('declarations.loginv3.post') !!}',
            },
            'v4_14857': {
                    url: '{!! route('declarations.v4.OldEncouragementSave_14857') !!}',
                    loginurl: '{!! route('declarations.loginv4.post') !!}',
                }

        };

        var stepManager = {
            currentStep: 'excel_set',
            work: function (step) {
                axios.get(steps[step].url).then(function (response) {
                    if (response.data) {
                        if (response.data.code == 'LOGIN_FAIL') {
                            console.log(steps[step].url+"|data key|"+response.data.key + "|step|"+ step);
                            //stepManager.currentStep = step;
                            //stepManager.work(response.data.step);
                            loginModal(steps[step].loginurl, response.data.key, step);
                        } else {
                            console.log('response.data.step');
                            console.log(response.data.step)
                            if (response.data.code == 'SUCCESS') {
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
                $('#loginModal').modal({ backdrop: 'static', keyboard: false, show: true });
                $('#loginModalForm').attr('action', targetUrl);
            }

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
