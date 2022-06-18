<div class="form-group">
    <label for="contact_by">İletişim Kurmak İstediğiniz Birimi Seçin</label>
    {!! Form::select("contact_by",  ['DESTEK' => 'TEKNİK DESTEK BİRİMİ', 'SATIS' => 'SATIŞ VE PAZARLAMA BİRİMİ', 'MUHASEBE' => 'MUHASEBE VE FİNANS BİRİMİ', 'DEMO' => 'DEMO İSTEĞİ'], isset($crm_support->contact_by) ? $crm_support->contact_by : null, ['class'=>'form-control input-xs','id'=>'contact_by']) !!}
</div>
<div class="form-group">
    <label for="email">İsim Soyisim</label>
    <input class="form-control form-control-lg" type="text" value="{{ isset($crm_support->name) ? $crm_support->name : null }}" id="name" name="name" required="required">
</div>
<div class="form-group">
    <label for="email">E-posta Adresiniz</label>
    <input class="form-control form-control-lg" type="email" value="{{ isset($crm_support->email) ? $crm_support->email : null }}" id="email" name="email" required="required">
</div>

<div class="form-group">
    <label for="email">Telefon Numaranız</label>
    <input class="form-control form-control-lg" type="text" value="{{ isset($crm_support->phone) ? $crm_support->phone : null }}" id="phone" name="phone">
</div>

<div class="form-group">
    <label for="email">Mesajınız</label>
    @if($crm_support->contact_by == 'DEMO')
        <textarea class="form-control p-0" rows="2"  id="message" required="required" name="message">{{ isset($crm_support->message) ? $crm_support->message : null }}</textarea>
    @else
        <textarea class="form-control p-0" rows="2"  id="message" required="required" name="message">{{ isset($crm_support->message) ? $crm_support->message : null }}[cevap]</textarea>
        <p class="help-block success">Kullanıcıya gitmesini istediğiniz mesajı [cevap] tan sonra yazabilirsiniz. [cevap] etiketi içermeden yazdıklarınız sistemde bilgi notu olarak kalacaktır.</p>

    @endif
</div>
@if($crm_support->contact_by == 'DEMO')
<div class="form-group">
    <label for="contact_by">Demo Talebi Kabul Edildi Mi?</label>
    {!! Form::select("status",  ['BEKLEMEDE' => 'BEKLEMEDE', 'AKTARILDI' => 'AKTARILDI', 'ÇÖZÜLDÜ' => 'ÇÖZÜLDÜ', 'DEMO ONAYLANDI' => 'DEMO ONAYLANDI'], null, ['class'=>'form-control input-xs','id'=>'status']) !!}
    <p class="help-block success">DEMO ONAYLANDI ise talepte bulunan kişi için demo firmasına bir demo kişisi hesabı oluşturulacak ve kişi e-posta ile bilgilendirilecektir.</p>
</div>
@endif
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
