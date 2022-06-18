@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Kimlik Bildirim Sistemi</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row method-incentive">
                                <div class="col-lg-12">
                                    <div class="card card-body border-top-1 border-top-pink">
                                        <div class="text-center">
                                            <h6 class="m-0 font-weight-semibold">Kimlik Bildirim Sistemi (KBS)</h6>
                                            <p class="text-muted mb-3">Kimlik Bildirim Sistemi / Çalışan Bildirimİ üzerinden İşyerinizdeki Aktif Çalışanları çeker.</p>
                                            <button type="button" onclick="stepManager.work('kimlik-bildirim');" class="btn btn-success start-incentive"><b><i class="icon-collaboration"></i></b> Devam Et</button>
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

                            <br/>
                            <div class="row">
                                <table class="table table-bordered" id="kt_datatable">
                                    <thead>
                                    <tr>
                                        <th>TC</th>

                                        <th>İşe Giriş Tarihi</th>
                                        <th>İşten Çıkış Tarihi</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    @if(Auth::user()->company_id != 134)
                                    @foreach($identity_notifications as $identity_notification)
                                        <tr>
                                            <td>{{ $identity_notification->tck }}</td>
                                            <td>{{ $identity_notification->ise_giris_tarihi->format('d/m/Y') }}</td>
                                            <td>{{ $identity_notification->isten_ayrilis_tarihi->format('d/m/Y') }}</td>

                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3">Veri bulunamamıştır...</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
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
@push('scripts')
    <script>
        var steps = {
            'kimlik-bildirim': {
                url: '{!! route('declarations.KbsCalisan.KbsCalisanCek') !!}',
                loginurl: '{!! route('declarations.KbsCalisan-post') !!}',
            }
        };

        var stepManager = {
            currentStep: 'kimlik-bildirim',
            work: function (step) {
                axios.get(steps[step].url).then(function (response) {
                    if (response.data) {
                        if (response.data.code == 'LOGIN_FAIL') {
                            loginModal(steps[step].loginurl, response.data.key, step);
                        } else {
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

                });
            },
            nextStep: function () {
            },
            advance: function () {
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
            if (captchaKey === 'undefined' ||captchaKey === undefined || captchaKey === null) {
                $("#step-info").html("Captcha alınamadı");
            } else {
                $("[name*='captcha']").val("");
                $('#captchaImg').attr('src', captchaKey);
                $('#loginModal').modal('show');
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
    </script>

@endpush
