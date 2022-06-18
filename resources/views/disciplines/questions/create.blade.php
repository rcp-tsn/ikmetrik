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
                                SORULARI DÜZENLE / EKLE
                            </h3>
                            <div class="card-toolbar">
                                    <div class="form-group" style="text-align: -webkit-right;margin-right: 20px; padding-top: 20px">
                                            <a href="{{ route('disciplineQuestions.index')}}">
                                            <div data-repeater-create=""  class="btn font-weight-bold btn-danger">
                                                <i class="la la-backspace"></i>Geri</div>
                                        </a>
                                </div>
                            </div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        <div class="card-body">
                            @include('partials.alerts.error')
                            @include('disciplines.questions.form')
                        </div>
                        <div class="card-footer">
                            <button type="button" id="QuestionsCreate" class="btn btn-primary mr-2">KAYDET</button>
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{ __('global.buttons.CancelButtonText') }}</a>
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

@endpush




