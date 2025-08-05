@php
    $now = \Carbon\Carbon::now()->format('d.m.Y H:i');
@endphp
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>Sipariş Raporu</title>
<style>
  @page{margin:25px 20px 55px 20px}
  body{font-family:DejaVu Sans,sans-serif;font-size:11px;margin:0}
  h2{margin:6px 0 0;font-size:16px;text-align:center}
  .date{font-size:10px;text-align:center;color:#555;margin-bottom:8px}
  hr{border:none;border-top:1px solid #777;margin:6px 0 10px}
  table{border-collapse:collapse;width:100%}
  th,td{border:0.5pt solid #000;padding:4px;text-align:left}
  thead tr{background:#f3f3f3}
  tbody tr:nth-child(even){background:#fafafa}
  footer{position:fixed;bottom:-30px;left:0;right:0;text-align:center;font-size:9px;color:#555}
  footer .pno:after{content:counter(page)}
</style></head><body>

{{-- Üst bant: Logo + tam iletişim --}}
<table style="width:100%;margin-bottom:8px">
  <tr>
    <td style="width:40%">
      <img src="{{ public_path('images/ika_logo.svg') }}" style="width:110px">
    </td>
    <td style="font-size:10px;padding-left:8px">
      <table>
        <tr><td style="width:80px"><strong>Adres:</strong></td><td>İstiklal Cd. No:123, İstanbul</td></tr>
        <tr><td><strong>Vergi No:</strong></td><td>1234567890</td></tr>
        <tr><td><strong>Telefon:</strong></td><td>+90 212 555 00 00</td></tr>
        <tr><td><strong>E-posta:</strong></td><td>info@ikacrm.com</td></tr>
        <tr><td><strong>Web:</strong></td><td>www.ikacrm.com</td></tr>
      </table>
    </td>
  </tr>
</table>

<h2>Sipariş Raporu
  @isset($range) ({{ $range[0] }} – {{ $range[1] }}) @endisset
</h2>
<p class="date">Oluşturulma: {{ $now }}</p><hr>

<table>
  <thead><tr>
      <th>Müşteri</th><th>Firma</th><th>Tip</th><th>Sipariş</th>
      <th>Teslim</th><th>Ödeme</th><th style="text-align:right">Toplam</th>
  </tr></thead>
  <tbody>
  @foreach($orders as $o)
    <tr>
      <td>{{ $o->customer->customer_name }}</td>
      <td>{{ $o->company->company_name ?? '—' }}</td>
      <td>{{ $o->order_type==='sale' ? 'Satış' : 'Alış' }}</td>
      <td>{{ $o->order_date?->format('d.m.Y') }}</td>
      <td>{{ $o->delivery_date?->format('d.m.Y') ?? '—' }}</td>
      <td>{{ $o->is_paid ? 'Ödendi' : 'Bekliyor' }}</td>
      <td style="text-align:right">{{ number_format($o->total_amount,2) }} ₺</td>
    </tr>
  @endforeach
    <tr>
      <td colspan="7" style="text-align:right;font-weight:bold">
        Toplam Sipariş: {{ $orders->count() }}
      </td>
    </tr>
  </tbody>
</table>

<footer>Sayfa <span class="pno"></span></footer>
</body></html>
