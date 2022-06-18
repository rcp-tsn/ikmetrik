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
            <div class="row mt-0 mt-lg-8">
                <div class="col-xl-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b ">

                        <!--begin::Card header-->
                        <div class="card-header h-auto border-0">
                            <div class="card-title py-5">
                                <h3 class="card-label">
                                    <span class="d-block text-dark font-weight-bolder">Diğer Sayfalar</span>
                                    <span class="d-block text-muted mt-2 font-size-sm">bu bölüm diğer sayfalardır</span>
                                </h3>
                            </div>

                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->

                            {!! Form::model($customer_email, ['route' => ['users.update-demo', $customer_email->id], 'method' => 'PUT', 'class' => 'form', 'id' => 'ezy_standard_form']) !!}
                        <div class="card-body">
                            @include('partials.alerts.error')
                            <div class="form-group">
                                <label>Yetkili</label>
                                {!! Form::text("customer_official", $customer_email->customer_official, ['class' => 'form-control form-control-solid', 'id' => 'customer_official']) !!}
                            </div>
                            <div class="form-group">
                                <label>E-posta</label>
                                {!! Form::text("email", $customer_email->email, ['class' => 'form-control form-control-solid', 'id' => 'email']) !!}
                            </div>
                            <div class="form-group">
                                <label>Telefon</label>
                                {!! Form::text("mobile", $customer_email->mobile, ['class' => 'form-control form-control-solid', 'id' => 'mobile']) !!}
                            </div>
                            <div class="form-group">
                                <label>Cep</label>
                                {!! Form::text("phone", $customer_email->phone, ['class' => 'form-control form-control-solid', 'id' => 'phone']) !!}
                            </div>
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

@push('scripts')
    <script>
        // Class definition
        var KTFormControls = function () {
            // Private functions
            var _initDemo1 = function () {
                FormValidation.formValidation(
                    document.getElementById('ezy_standard_form'),
                    {
                        fields: {
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: 'Eposta alanı gerekli'
                                    }
                                }
                            },
                        },

                        plugins: { //Learn more: https://formvalidation.io/guide/plugins
                            trigger: new FormValidation.plugins.Trigger(),
                            // Bootstrap Framework Integration
                            bootstrap: new FormValidation.plugins.Bootstrap(),
                            // Validate fields when clicking the Submit button
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            // Submit the form when all fields are valid
                            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        }
                    }
                );
            }


            return {
                // public functions
                init: function() {
                    _initDemo1();
                }
            };
        }();

        jQuery(document).ready(function() {
            KTFormControls.init();
        });

    </script>

@endpush

