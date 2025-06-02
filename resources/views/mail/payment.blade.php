<x-mail::message>
# Halo, {{ $transaction->name }}
Terimakasih sudah berbelanja di toko kami. <br>
Berikut rincian pesanan anda :
<x-mail::table>
|       No. Transaksi     |
| :---------------------: |
| #{{ $transaction->no }} |
</x-mail::table>
<x-mail::table>
|           Jumlah            |                    Harga                |
| ----------------------------|----------------------------------------:|
| {{ $transaction->qty }} pcs | @currency($transaction->payment->total) |
</x-mail::table>
Sedikit lagi pesanan kamu bisa diproses.<br>
Harap segera melakukan pembayaran 
dengan klik dibawah ini.
 
<x-mail::button :url="$url">
Ke Halaman Pembayaran
</x-mail::button>
 
Terimakasih,<br>
{{ config('app.name') }}
</x-mail::message>