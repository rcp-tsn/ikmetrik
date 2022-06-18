<div class="row">
    <div class="col-md-4">
        {{ Form::bsText('title', 'Paket Adı', $packet->title, ['id' => 'title']) }}
    </div>
    <div class="col-md-4">
        {{ Form::bsPrice('price', 'Fiyat', $packet->price, ['id' => 'price', 'required' => true]) }}
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        {{ Form::bsNumber('max_user_number', 'Max Kullanıcı Sayısı', $packet->max_user_number, ['id' => 'max_user_number', 'required' => true]) }}
    </div>
    <div class="col-md-6">
        {{ Form::bsNumber('period', 'Periyot', $packet->period, [], 'Paketin geçerli olacağı periyot ay olarak sayı giriniz.') }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {{ Form::bsMultiSelect('modules[]', 'Modüller', $modules, $selectModules, ['multiple'=>'multiple', 'data-live-search' => 'true']) }}
    </div>
</div>
<style>
    .ms-container {
        width: 100%;
    }
</style>

@push('scripts')
    <script>

        $(".price").TouchSpin({

        });
        $(".number").TouchSpin({

        });
        // Class definition
        var KTFormControls = function () {
            // Private functions
            var _initDemo1 = function () {
                FormValidation.formValidation(
                    document.getElementById('ezy_standard_form'),
                    {
                        fields: {
                            title: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.title") }} alanı gerekli'
                                    },
                                }
                            },
                            price: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.price") }} alanı gerekli'
                                    },
                                }
                            },

                            max_user_number: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.max_user_number") }} alanı gerekli'
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

