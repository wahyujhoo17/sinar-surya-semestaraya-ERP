@component('mail::message')
# Halo, {{ $name }}!

Anda menerima email ini karena kami menerima permintaan untuk mengatur ulang password akun SemestaPro Anda.

Silakan klik tombol di bawah ini untuk mengatur ulang password Anda:

@component('mail::button', ['url' => $url])
Atur Ulang Password
@endcomponent

Tautan atur ulang password ini akan kedaluwarsa dalam **{{ $count }} menit**.

Jika Anda tidak meminta pengaturan ulang password, silakan abaikan email ini atau hubungi tim keamanan kami jika Anda mencurigai adanya aktivitas tidak sah pada akun Anda.

Salam Hangat,<br>
Tim Keamanan SemestaPro

@slot('subcopy')
Jika Anda mengalami kendala saat mengklik tombol "Atur Ulang Password", silakan salin dan tempel URL di bawah ini ke peramban web (browser) Anda:  
[{{ $url }}]({{ $url }})
@endslot
@endcomponent
