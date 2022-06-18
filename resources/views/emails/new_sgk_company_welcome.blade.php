@component('mail::message')
# İkmetrik Süreç Bilgilendirmesi
Sayın <b>{{ $user->name }}</b>;<br />İK METRİK sistemimize yeni bir firma ({{ $sgk_company->name }}) kaydı oluşturdunuz. İlgili firmaya ait metrik bilgilerine 1 (bir) iş günü sonra ulaşabilirsiniz. Bu süre içerisinde bizler sistemi size hazır hale getirmeye çalışıyor olacağız. Tüm soru ve önerileriniz için bizimle iletişime geçebilirsiniz.<br/><br/>Destek ihtiyaçlarınız için yazılım içerisinden canlı destek veya <a href="mailto:destek@ikmetrik.com?subject=Destek Talebi">destek@ikmetrik.com</a> adresine e-posta gönderebilirsiniz.


@component('mail::button', ['url' => route('root')])
Panel Girişi
@endcomponent

@endcomponent
