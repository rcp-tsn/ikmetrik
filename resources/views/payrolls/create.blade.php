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
                                    <span class="d-block text-dark font-weight-bolder">E-bordro</span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row method-incentive">
                                <div class="col-lg-12">
                                    <div class="card card-body border-top-1 border-top-pink">
                                        <div class="text-center">
                                            <h6 class="m-0 font-weight-semibold">PDF ile İlerler </h6>
                                            <h6 class="m-0 font-weight-semibold font-size-14 text-danger">E-BORDRO İLE ARTIK DAHA KOLAY</h6>
                                            <p class="text-muted mb-3">TÜM BORDROLARINIZ ZAMAN DAMGASI İLE TÜM PERSONELLERE DAĞITILACAKTIR.</p>

                                            <div class="form-group">
                                                <input type="file" id="file-input-ajax-pdf"  accept=".pdf" name="pdf" data-preview-file-type="text" >
                                            </div>

                                            <div class="form-group">
                                                <button type="button" onclick="stepManager.work('pdf_Set');" class="btn btn-success start-incentive" id="start-incentive" style="display: none;">
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
@push('scripts')

    <script>
        var steps = {
            'pdf_Set': {
                url: '{!! route('payrolls.payrollStore') !!}',
                loginurl: '',
            },
            'payrollParse2':
                {
                    url: '{!! route('payrolls.payrollStore2') !!}',
                    loginurl: '',
                }
        };

        var stepManager = {
            currentStep: 'pdf_set',
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
                            console.log(response.data)
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

        $(".start-incentive").click(function () {
            $('.method-incentive').hide(500);

        });
        var uploadURL = "{{route('payrolls.payrollSave')}}";

    </script>

@endpush
