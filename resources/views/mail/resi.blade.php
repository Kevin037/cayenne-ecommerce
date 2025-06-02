<x-mail::message>
# Halo, {{ $transaction->name }}
No. Resi Pesanan anda sudah diperbarui. <br>

<x-mail::table>
| No. Resi Pengiriman                   |
| :-----------------------------------: |
| {{ $transaction->delivery->no }}      |
</x-mail::table>

Status pesanan anda bisa diakses dengan klik dibawah ini.
 
<x-mail::button :url="$url">
Ke Halaman Pesanan
</x-mail::button>

Jika ada pertanyaan, bisa menghubungi admin dengan klik dibawah ini.
<x-mail::button :url="$url_admin">
Hubungi Admin
</x-mail::button>

Terimakasih,<br>
{{ config('app.name') }}
</x-mail::message>