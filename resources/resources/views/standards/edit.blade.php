@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    @php $routeParts = explode('.', Route::currentRouteName()); @endphp
    @php $converted = isset(${str_singular($routeParts[0])}) ? ${str_singular($routeParts[0])} : ${camel_case(str_singular($routeParts[0]))};
    @endphp
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
                                {{ __($routeParts[0].'.create_form_title') }}
                            </h3>
                            <div class="card-toolbar"><!--
                                <button type="reset" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            --></div>
                        </div>

                        <!--end:: Card header-->
                        <!--begin::Card body-->
                        {!! Form::model($converted, ['route' => [$routeParts[0] . '.update', $converted], 'method' => 'PUT', 'class' => 'form', 'id' => 'ezy_standard_form']) !!}
                        <div class="card-body">
                            @include('partials.alerts.error')
                            @include('forms.' . str_singular($routeParts[0]))
                        </div>
                        <div class="card-footer">
                            {{ Form::bsUpdate(__('global.buttons.UpdateButtonText')) }}

                            <a href="{{ URL::previous() }}" class="btn btn-light-success font-weight-bold">{{ __('global.buttons.CancelButtonText') }}</a>
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

