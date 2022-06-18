<div class="form-group">
    <label>Sektör Adı</label>
    {!! Form::text("name", $sector->title, ['class' => 'form-control form-control-solid', 'id' => 'name']) !!}
</div>

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
