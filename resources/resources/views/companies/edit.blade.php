@extends('layouts.app')
@section('content')
    @component('layouts.partials.subheader-v1', ['bir'=> 'Anasayfa', 'iki' => 'Tmp sayfası'])
    @endcomponent
    @php $routeParts = explode('.', Route::currentRouteName()); @endphp
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
                        {!! Form::open(['route' => ['companies.update', $company], 'method' => 'put', 'files'=>'true', 'class' => 'form', 'id' => 'ezy_standard_form']) !!}

                        <div class="card-body">
                            @include('partials.alerts.error')
                            @include('companies._form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success font-weight-bold mr-2">{{ __('global.buttons.UpdateButtonText') }}</button>
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

@push('scripts')
    @include('partials.map_modal')
    <script type="text/javascript" src="{{ asset('assets/js/gmap.js') }}"></script>
    <script>
        function addCoordinates() {
            $('#lat').val( $('.inputLat').val() );
            $('#long').val( $('.inputLng').val() );
            $('#getCoordinatesFromMapModal').modal('hide');
        }
    </script>
@endpush




