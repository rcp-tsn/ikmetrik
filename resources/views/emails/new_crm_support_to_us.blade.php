@component('mail::message')
# Destek Talebi
Sayın <b>Yetkili </b>;<br />İK METRİK sistemimize aşağıdaki bilgiler ile bir destek talebi oluşturulmuştur.

@component('mail::panel')
    <p><b>İsim Soyisim:</b> {{ $crmSupport->name }}</p>
    <p><b>Firma:</b> {{ $crmSupport->company }}</p>
    <p><b>E-posta:</b> {{ $crmSupport->email }}</p>
    <p><b>Telefon:</b> {{ $crmSupport->phone }}</p>
    <p><b>Mesaj:</b> {{ $crmSupport->message }}</p>
    <p><b>Birim:</b> {{ $crmSupport->contact_by }}</p>
@endcomponent

@endcomponent
