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
                        <div class="card-header" style="font-size: 15px;font-weight: bold">
                            <h3 class="card-title" style="font-size: 15px;font-weight: bold">
                                Eş Değer Personel Değerlendirme
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => [ 'question_evalation_store','id' => createHashId($program_id),'top_manager' => 'sdfs', 'type' => 'job_criteria'], 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}
                        <div class="card-body">
                            @include('partials.alerts.error')

                            <div class="d-flex flex-column-fluid">
                                <!--begin::Container-->
                                <div class="container-fluid">
                                    <div class="card card-custom card-transparent">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="container-fluid">
                                                        <div class="card card-custom card-transparent flex-wrap bannerr" id="bannerr" style="position: fixed;width: 22%;">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Değerlendirme Yapılan : {{$employee->first_name}} {{$employee->last_name}}</h3>

                                                            </div>
                                                            <div class="card-body">
                                                                <label style="font-weight: bold">Test Durumu:</label>
                                                                <div class="progress" style="height: 25px;font-size: 15px">
                                                                    <div class="progress-bar progress_class" role="progressbar" style="width: 0%;background-color: orange" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">75%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8">
                                                    <div class="container-fluid">
                                                        <div class="card card-custom card-transparent">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Yetkinlik Sorularınız</h3>

                                                            </div>
                                                            <div class="card-body">
                                                                @php $soru = 1; @endphp
                                                                @foreach($questions as $key =>  $question)
                                                                    <div class="question_count">
                                                                        <div class="form-group">
                                                                            <h4>Soru:    {{$soru++}}</h4>
                                                                        </div>

                                                                        <div class="form-group" id="next{{$key}}">
                                                                            <label  class="control-label" style="font-size: 15px;font-weight: bold">{{$question->question}} ? </label>
                                                                            <div class="form-group" style="margin-left: 15px">
                                                                                <div class="radio-list mt-5">
                                                                                    <label class="radio">
                                                                                        <input id="" data-id="{{$key}}" type="radio" class="question" value="{{isset($puanlar[1]) ? $puanlar[1] : 1  }}" name="radios[{{$question->id}}]">
                                                                                        <span></span>Eğitim Almakta(Acemi)</label>
                                                                                    <label class="radio ">
                                                                                        <input type="radio" data-id="{{$key}}" class="question" value="{{isset($puanlar[2]) ? $puanlar[2] : 2  }}"  name="radios[{{$question->id}}]">
                                                                                        <span></span>Kontrol Altında Çalışabilir(Orta)</label>
                                                                                    <label class="radio">
                                                                                        <input type="radio" data-id="{{$key}}" class="question" value="{{isset($puanlar[3]) ? $puanlar[3] : 3  }}"  name="radios[{{$question->id}}]">
                                                                                        <span></span>Tek Başına Çalışabilir(pilot)</label>
                                                                                    <label class="radio">
                                                                                        <input type="radio" data-id="{{$key}}" class="question" value="{{$tob_puan}}"  name="radios[{{$question->id}}]">
                                                                                        <span></span>Eğitmen(Uzman)</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="evalation_id" value="{{$employee->id}}">
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">{{ __('global.buttons.SaveButtonText') }}</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>
                    {!! Form::close() !!}
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

@section('js')
    <script>

        $('.question').click(function ()
        {
            var id = $(this).data('id');
            console.log(id);
            var next = Number(id + 1) ;
            $(document).scrollTo('#next'+id,1000);
        });



    </script>

    <script>
        var count = $(".question_count").length;
        var click = 0;
        var key = [];
        $('.question').click(function ()
        {

            if (jQuery.inArray($(this).data('id'), key) !== -1)
            {
                var deger = 0;
                deger  = (click * 100)/count;
                $(".progress_class").css("width",deger+"%");
            }
            else
            {

                key.push($(this).data('id'));
                click = click + 1;
                var deger = 0;
                deger  = (click * 100)/count;
                $(".progress_class").css("width",deger+"%");
                $(".progress_class").html(deger+"%");
            }


        });
    </script>
@endsection

