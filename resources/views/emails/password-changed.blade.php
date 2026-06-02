@component('mail::message')
# Halo, {{ $name }}!

Kami ingin memberitahukan bahwa password untuk akun SemestaPro Anda baru saja berhasil diubah.

Berikut adalah rincian aktivitas keamanan perubahan password Anda:

<div style="margin: 20px 0;">
    <table style="width: 100%; border-collapse: collapse; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px;">
        <tr style="border-bottom: 1px solid #edf2f7;">
            <td style="padding: 12px 16px; font-weight: bold; color: #475569; width: 35%;">Waktu</td>
            <td style="padding: 12px 16px; color: #0f172a;">{{ $time }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #edf2f7;">
            <td style="padding: 12px 16px; font-weight: bold; color: #475569;">IP Address</td>
            <td style="padding: 12px 16px; color: #0f172a; font-family: monospace;">{{ $ip }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #edf2f7;">
            <td style="padding: 12px 16px; font-weight: bold; color: #475569;">Lokasi Perkiraan</td>
            <td style="padding: 12px 16px; color: #0f172a;">{{ $location }}</td>
        </tr>
        <tr>
            <td style="padding: 12px 16px; font-weight: bold; color: #475569;">Browser / Perangkat</td>
            <td style="padding: 12px 16px; color: #0f172a;">{{ $browser }}</td>
        </tr>
    </table>
</div>

Jika Anda memang merasa melakukan perubahan ini, silakan abaikan email ini.

<div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 6px; margin: 20px 0; color: #78350f; font-size: 14px; text-align: left; line-height: 1.5;">
    <strong style="display: block; margin-bottom: 4px; font-size: 15px; color: #b45309;">⚠️ PENTING: Tindakan Diperlukan Jika Ini Bukan Anda</strong>
    Jika Anda <strong>tidak merasa</strong> melakukan perubahan atau menyetel ulang password ini, harap segera hubungi Administrator atau tim IT support kami untuk mengamankan akun Anda demi mencegah penyalahgunaan.
</div>

Salam Hangat,<br>
Tim Keamanan SemestaPro
@endcomponent
