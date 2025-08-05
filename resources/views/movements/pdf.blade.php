@php
    $now  = \Carbon\Carbon::now()->format('d.m.Y H:i');
    // Eski → yeni sıralama
    $rows = collect($movements)->sortBy([['departure_date','asc'], ['id','asc']]);
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Hesap Hareketleri</title>
    <style>
        @page { margin:25px 20px 55px 20px }
        body   { font-family:DejaVu Sans, sans-serif; font-size:11px; margin:0 }
        h2     { margin:6px 0 0; font-size:16px; text-align:center }
        .date  { font-size:10px; text-align:center; color:#555; margin-bottom:8px }
        hr     { border:none; border-top:1px solid #777; margin:6px 0 10px }
        table  { border-collapse:collapse; width:100% }
        th,td  { border:0.5pt solid #000; padding:4px }
        thead tr { background:#f3f3f3 }
        tbody tr:nth-child(even){ background:#fafafa }
        .text-end   { text-align:right }
        .text-danger{ color:#c00 }
        .text-success{ color:#090 }
        footer { position:fixed; bottom:-30px; left:0; right:0; text-align:center; font-size:9px; color:#555 }
        footer .pno:after { content:counter(page) }
    </style>
</head>
<body>

{{-- Üst bant --}}
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
        <tr><td><strong>E‑posta:</strong></td><td>info@ikacrm.com</td></tr>
        <tr><td><strong>Web:</strong></td><td>www.ikacrm.com</td></tr>
        <tr><td><strong>Instagram:</strong></td><td>@ikacrm</td></tr>
      </table>
    </td>
  </tr>
</table>

<h2>Hesap Hareketleri
  @isset($range) ({{ $range[0] }} – {{ $range[1] }}) @endisset
  @isset($type) – {{ $type==='Debit' ? 'Alış' : 'Satış' }} @endisset
</h2>
<p class="date">Oluşturulma : {{ $now }}</p>
<hr>

<table>
    <thead>
        <tr>
            <th style="width:85px">Tarih</th>
            <th class="text-end" style="width:90px">Alış</th>
            <th class="text-end" style="width:90px">Satış</th>
            <th class="text-end" style="width:90px">Bakiye</th>
            <th>Açıklama</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $m)
            <tr>
                <td>{{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</td>
                <td class="text-end text-danger">{{ $m->movement_type==='Debit' ? number_format($m->amount,2,',','.') : '' }}</td>
                <td class="text-end text-success">{{ $m->movement_type==='Credit' ? number_format($m->amount,2,',','.') : '' }}</td>
                <td class="text-end">{{ number_format($m->running_balance,2,',','.') }}</td>
                <td>{{ $m->explanation }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<footer>Sayfa <span class="pno"></span></footer>
</body>
</html>
