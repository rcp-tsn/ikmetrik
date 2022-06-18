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
                        <div class="card-header">
                            <h3 class="card-title">
                                Yeni Çalışan Ekle
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => [ 'question_evalation_store','id' => createHashId($id),'top_manager' => createHashId($top_manager->id),'type' => 'top_manager'], 'files'=>'true', 'class' => 'form', 'id' => 'kt_form']) !!}
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
                                                        <div class="card card-custom card-transparent">
                                                            <div class="card-header">
                                                                <h3 class="card-title" style="font-weight: bold">Üst Yöneticiniz : {{$top_manager->first_name}} {{$top_manager->last_name}}</h3>

                                                            </div>
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                                                                    <span class="font-weight-bold text-dark-75">Progress</span>
                                                                    <div class="progress progress-xs mx-3 w-100">
                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 63%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                    <span class="font-weight-bolder text-dark">78%</span>
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
                                                        <div class="form-group">
                                                            <h4>Soru:    {{$soru++}}</h4>
                                                        </div>

                                                        <div class="form-group" id="next{{$key}}">
                                                            <label  class="control-label">{{$question->question}} ? </label>
                                                            <div class="form-group" style="margin-left: 15px">
                                                                <div class="radio-list mt-5">
                                                                    <label class="radio">
                                                                        <input id="" data-id="{{$key}}" type="radio" class="question" value="1" name="radio[{{$question->id}}][1]">
                                                                        <span></span>Yetersiz</label>
                                                                    <label class="radio ">
                                                                        <input type="radio" data-id="{{$key}}" class="question" value="2"  name="radio[{{$question->id}}][2]">
                                                                        <span></span>Orta</label>
                                                                    <label class="radio">
                                                                        <input type="radio" data-id="{{$key}}" class="question" value="3"  name="radio[{{$question->id}}][3]">
                                                                        <span></span>İyi</label>
                                                                    <label class="radio">
                                                                        <input type="radio" data-id="{{$key}}" class="question" value="4"  name="radio[{{$question->id}}][4]">
                                                                        <span></span>Mükemmel</label>
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

            $('.question').click = function() {
                var id = $(this).data('id');
                console.log(id);
                var next = Number(id + 1) ;
                $(document).scrollTo('#next'+next);
            }

    </script>
@endsection

