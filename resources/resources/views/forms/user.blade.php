
@if(!isset($user->id))
    {{ Form::bsSelect('company_id', 'Firma', $selectCompanies, $currentCompany, [], ['required' => true], ['blankSelect' => true]) }}
@endif
{{ Form::bsText('name', 'Ad - Soyad', $user->name) }}
{{ Form::bsText('email', 'E-mail Adresi', $user->email, ['required' => true]) }}
{{ Form::bsMultiSelect2('role[]', 'Rol', $roles, $currentRole, [], ['required' => true], []) }}
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
                                        message: '{{ __($routeParts[0].".fields.name") }} alanı gerekli'
                                    },
                                }
                            },
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.email") }} alanı gerekli'
                                    },
                                }
                            },
                            role: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.role") }} alanı gerekli'
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

