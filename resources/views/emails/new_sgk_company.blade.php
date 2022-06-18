@component('mail::message')
# Yeni SGK Firması Girildi
Sisteme yeni bir sgk firması tanımlaması yapıldı.

@component('mail::panel')
<p><b>Üst Firma:</b> {{ $sgk_company->company->name }}</p>
<p><b>Firma:</b> {{ $sgk_company->name }}</p>
@endcomponent


@endcomponent
