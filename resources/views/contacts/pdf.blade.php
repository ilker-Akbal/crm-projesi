@php
    $now = \Carbon\Carbon::now()->format('d.m.Y H:i');
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>Kişi Listesi</title>

    <style>
        @page { margin:25px 20px 55px 20px; }

        body   { font-family:DejaVu Sans, sans-serif; font-size:11px; margin:0; }
        h2     { margin:6px 0 0; font-size:16px; text-align:center; }
        .date  { font-size:10px; text-align:center; color:#555; margin-bottom:8px; }
        hr     { border:none; border-top:1px solid #777; margin:6px 0 10px; }

        table  { border-collapse:collapse; width:100%; }
        th,td  { border:0.5pt solid #000; padding:4px; text-align:left; }
        thead tr { background:#f3f3f3; }
        tbody tr:nth-child(even) { background:#fafafa; }

        footer { position:fixed; bottom:-30px; left:0; right:0; text-align:center;
                 font-size:9px; color:#555; }
        footer .pno:after { content:counter(page); }
    </style>
</head>
<body>

{{-- ---------- Üst Bant: Logo + Şirket Bilgileri ---------- --}}
<table style="width:100%; margin-bottom:8px;">
    <tr>
        <td style="width:40%;">
            <img src="{{ public_path('images/ika_logo.svg') }}" style="width:110px;">
        </td>
        <td style="font-size:10px; padding-left:8px;">
            <table>
                <tr><td style="width:80px;"><strong>Adres:</strong></td><td>İstiklal Cd. No:123, İstanbul</td></tr>
                <tr><td><strong>Vergi No:</strong></td><td>1234567890</td></tr>
                <tr><td><strong>Telefon:</strong></td><td>+90&nbsp;212&nbsp;555&nbsp;00&nbsp;00</td></tr>
                <tr><td><strong>E-posta:</strong></td><td>info@ikacrm.com</td></tr>
                <tr><td><strong>Web:</strong></td><td>www.ikacrm.com</td></tr>
                <tr><td><strong>Instagram:</strong></td><td>@ikacrm</td></tr>
            </table>
        </td>
    </tr>
</table>

{{-- ---------- Başlık ---------- --}}
<h2>Kişi Listesi
    @isset($range) ({{ $range[0] }} – {{ $range[1] }}) @endisset
</h2>
<p class="date">Oluşturulma : {{ $now }}</p>
<hr>

{{-- ---------- İçerik Tablosu ---------- --}}
<table>
    <thead>
        <tr>
            <th>Ad</th>
            <th>Pozisyon</th>
            <th>Telefon</th>
            <th>E-posta</th>
            <th>Firma</th>
            <th>Eklenme</th>
        </tr>
    </thead>
    <tbody>
        @forelse($contacts as $ct)
            <tr>
                <td>{{ $ct->name }}</td>
                <td>{{ $ct->position }}</td>
                <td>{{ $ct->phone }}</td>
                <td>{{ $ct->email }}</td>
                <td>{{ $ct->company?->company_name ?? '—' }}</td>
                <td>{{ $ct->created_at->format('d.m.Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Kayıt bulunamadı.</td></tr>
        @endforelse
    </tbody>
</table>

<footer>Sayfa <span class="pno"></span></footer>
</body>
</html>
