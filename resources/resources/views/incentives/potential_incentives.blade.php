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
                                    <span class="d-block text-dark font-weight-bolder">Potansiyel Teşvik Sorgulama</span>
                                </h3>
                            </div>
                        </div>
                        <form action="/declarations/incentives/potential-incentives" method="POST" class="form" id="ezy_standard_form">
                            @csrf
                        <div class="card-body">
                            @include('partials.alerts.error')
                            <div class="form-group">
                                <label>T.C.Kimlik Numarası</label>
                                {!! Form::text("tck", null, ['class' => 'form-control form-control-solid', 'id' => 'tck']) !!}
                            </div>
                        </div>
                        <div class="card-footer">
                            {{ Form::bsSubmit('SORGULA') }}
                            <a href="{{ URL::previous() }}" class="btn btn-secondary font-weight-bold">{{ __('global.buttons.CancelButtonText') }}</a>
                        </div>
                        </form>
                    </div>
                    <div class="panel-body">
                        @if(count($potential_icitement_result) > 0)
                            <div  id="accordion-styled">
                                @for($i = 1; $i < count($potential_icitement_result); $i++)
                                    <div class="card">
                                        <table class="table table-bordered">
                                            @php
                                                $result = str_replace('style="display : none;"', '',  $potential_icitement_result[$i]);
                                                $result = str_replace('b3ffd9', '#b3ffd9',  $result);
                                                $result = str_replace('ffcce0', '#ffcce0',  $result);
                                                $result = str_replace('cursor: pointer;', '',  $result);
                                                $result = str_replace('thalign', 'th align',  $result);

                                            @endphp
                                            {!! $result !!}
                                        </table>
                                    </div>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('modal')

@endpush
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
                            name: {
                                validators: {
                                    notEmpty: {
                                        message: 'alanı gerekli'
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
