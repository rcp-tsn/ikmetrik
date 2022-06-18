@extends('layouts.app')
@section('content')
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
                                Parola Değiştir
                            </h3>
                            <div class="card-toolbar"></div>
                        </div>

                        <!--end:: Card header-->

                        <!--begin::Card body-->
                        {!! Form::open(['route' => 'save-password', 'class' => 'form', 'id' => 'ezy_standard_form']) !!}
                        <div class="card-body">
                            @include('partials.alerts.error')
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-custom gutter-b ">
                                        <div class="card-header">
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password" class="control-label">Şifre</label>
                                                        <input class="form-control" required placeholder="Parola" name="password" type="password" id="password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="password_confirmation" class="control-label">Şifre Kontrol</label>
                                                        <input class="form-control" placeholder="Tekrar" name="password_confirmation" required type="password" id="password_confirmation">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
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
    <script>
        // Class definition
        var KTFormControls = function () {
            // Private functions
            var _initDemo1 = function () {
                FormValidation.formValidation(
                    document.getElementById('ezy_standard_form'),
                    {
                        fields: {
                            password: {
                                validators: {
                                    notEmpty: {
                                        message: 'Parola alanı gerekli'
                                    },
                                }
                            },
                            password_confirmation: {
                                validators: {
                                    notEmpty: {
                                        message: 'Parola doğrulama alanı gerekli'
                                    },
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




