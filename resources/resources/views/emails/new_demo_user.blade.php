@component('mail::message')
# İKMETRİK Ailesine Hoşgeldiniz
Sayın <b>{{ $user->name }}</b>;<br />Şirketimiz tarafından oluşturulan İK METRİK demo kullanıcı bilgileriniz ile  aşağıdaki butona tıklayarak giriş yapabilirsiniz.<br/><br/>Destek ihtiyaçlarınız için yazılım içerisinden canlı destek veya <a href="mailto:destek@ikmetrik.com?subject=Destek Talebi">destek@ikmetrik.com</a> adresine e-posta gönderebilirsiniz.

@component('mail::panel')
<p><b>E-posta:</b> {{ $user->email }}</p>
<p><b>Parola:</b> {{ $password }}</p>
@endcomponent
@component('mail::button', ['url' => route('root')])
    Panel Girişi
@endcomponent

@endcomponent
