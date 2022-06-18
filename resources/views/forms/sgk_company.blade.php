<div class="row">
    <div class="col-md-6">
        {{ Form::bsSelect('company_id', 'Bağlı Olduğu Firma', $parent_sgk_companies, $sgk_company->company_id, [], ['required' => true], []) }}
    </div>
    <div class="col-md-6">
        {{ Form::bsText('name', 'Firma Adı', $sgk_company->name) }}
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        {{ Form::bsSelect('sector_id', 'Sektör', $sectors, $sgk_company->sector_id, [], ['required' => true], []) }}
    </div>
    <div class="col-md-6">
        {{ Form::bsSelect('city_id', 'Şehir', $cities, $sgk_company->city_id, [], ['required' => true], []) }}
    </div>
</div>
<div class="row">
    @if(config('app.main_company_id') != Auth::user()->company_id)

    @else
    <div class="col-md-6">

        {{ Form::bsNumber('company_gain_ratio', 'Çalışma Oranı %', $sgk_company->company_gain_ratio, ['id' => 'company_gain_ratio', 'required' => true]) }}
    </div>
    @endif
    <div class="col-md-6">
        {{ Form::bsText('registry_id', 'Firma Sicil No', $sgk_company->registry_id) }}
    </div>
</div>
@if($sgk_company->id > 0)
    @else
    <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
    @endif
@if(config('app.main_company_id') != Auth::user()->company_id)
    <input type="hidden" name="is_metrik_customer" value="1">
@endif

<div class="row">
    <div class="col-md-3">
        {{ Form::bsText('company_username', 'Kullanıcı Adı', $sgk_company->company_username) }}
    </div>
    <div class="col-md-3">
        {{ Form::bsText('company_usercode', 'Yan Kod', $sgk_company->company_usercode) }}
    </div>
    <div class="col-md-3">
        {{ Form::bsText('company_syspassword', 'Sistem Şifresi', $sgk_company->company_syspassword) }}
    </div>
    <div class="col-md-3">
        {{ Form::bsText('company_password', 'İşyeri Şifresi', $sgk_company->company_password) }}
    </div>
</div>

@if(Auth::user()->company_id == config('app.main_company_id'))
<div class="row">
    <div class="col-md-3">
        {{ Form::bsSelect('is_completed', 'İlk Tarama İşlemi Yapıldı mı?', [0 => 'HAYIR', 1 => 'EVET'], null, [], []) }}
    </div>
</div>
@endif
<h3 class="font-size-lg text-dark font-weight-bold mb-6">KİMLİK BİLDİRİM SİSTEMİ / Erişim Bilgileri</h3>
<div class="alert alert-custom alert-default" role="alert">
    <div class="alert-icon">
																<span class="svg-icon svg-icon-primary svg-icon-xl">
																	<!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Tools/Compass.svg-->
																	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																		<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24"></rect>
																			<path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
																			<path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
																		</g>
																	</svg>
                                                                    <!--end::Svg Icon-->
																</span>
    </div>
    <div class="alert-text">Bu bölüm isteğe bağlıdır. Kimlik Bildirim bilgisinın alınması istenildiği takdirde doldurulması gerekmektedir.</div>
</div>
<div class="row">
    <div class="col-md-3">
        {{ Form::bsText('kbcalisan_email', 'Kullanıcı Mail Adresi', $sgk_company->kbcalisan_email) }}
    </div>
    <div class="col-md-3">
        {{ Form::bsText('kbcalisan_sifre', 'Şifre', $sgk_company->kbcalisan_sifre) }}
    </div>
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
                                    },
                                }
                            },
                            sector_id: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.sector_id") }} alanı gerekli'
                                    },
                                }
                            },
                            company_id: {
                                validators: {
                                    notEmpty: {
                                        message: '{{ __($routeParts[0].".fields.company_id") }} alanı gerekli'
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

