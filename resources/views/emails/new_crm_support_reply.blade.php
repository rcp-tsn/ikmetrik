@component('mail::message')
# Destek Talebiniz Cevaplandı
Sayın <b>{{ $crmSupport->name }}</b>;<br />İK METRİK sistemimize oluşturmuş olduğunuz destek talebi cevaplanmıştır.

@component('mail::panel')
    <p><b>Talebiniz:</b> {{ $reply }}</p>
    <p><b>Cevabımız:</b> {{ $result }}</p>
@endcomponent
@endcomponent
