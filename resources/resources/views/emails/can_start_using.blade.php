@component('mail::message')
# İkmetrik Süreç Bilgilendirmesi
Sayın <b>{{ $user->name }}</b>;<br />İK METRİK sistemimize yeni bir firma ({{ $sgk_company->name }}) tanımlama yapmıştınız. Firmanıza ait veriler ilgili sistemlerden çekilerek kullanıma hazır hale gelmiştir. Artık İK Metrik deneyimine başlayabilirsiniz. Tüm soru ve önerileriniz için bizimle iletişime geçebilirsiniz.<br/><br/>Destek ihtiyaçlarınız için yazılım içerisinden canlı destek veya <a href="mailto:destek@ikmetrik.com?subject=Destek Talebi">destek@ikmetrik.com</a> adresine e-posta gönderebilirsiniz.


@component('mail::button', ['url' => route('root')])
Panel Girişi
@endcomponent

@endcomponent
