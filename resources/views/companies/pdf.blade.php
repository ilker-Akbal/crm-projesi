@php
    $now = \Carbon\Carbon::now()->format('d.m.Y H:i');

    /* —— Şirket bilgileri —— */
    $companyMeta = [
        'Adres'      => 'Aydın/Efeler',
        'Vergi No'   => '1111111111',
        'Telefon'    => '+90 5555555555',
        'E-posta'    => 'info@ikacrm.com',
        'Web'        => 'www.ikacrm.com',
        'Instagram'  => '@ikacrm',
    ];
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Firma Raporu</title>

    <style>
        @page { margin: 25px 20px 45px 20px; }

        body   { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 0; }
        table  { border-collapse: collapse; width: 100%; }
        th,td  { border: 0.5pt solid #000; padding: 4px; text-align: left; }

        thead tr    { background: #f3f3f3; }
        tbody tr:nth-child(even) { background: #fafafa; }

        /* Üst bandı iki sütunlu yap */
        .topband { width: 100%; margin-bottom: 10px; }
        .logo    { width: 35%; }
        .meta    { width: 65%; font-size: 10px; line-height: 1.35; }
        .meta td { border: none; padding: 1px 0; }

        h2       { margin: 6px 0 0 0; font-size: 16px; text-align: center; }
        .date    { font-size: 10px; text-align: center; color: #555; margin-bottom: 10px; }

        hr       { border: none; border-top: 1px solid #777; margin: 8px 0 12px; }

        footer   { position: fixed; bottom: -25px; left: 0; right: 0;
                   text-align: center; font-size: 9px; color: #555; }
        footer .pno:after { content: counter(page); }
    </style>
</head>
<body>

    {{-- ---------- Üst Bant: Logo + Meta ---------- --}}
    <table class="topband">
        <tr>
            <td class="logo">
                <img src="{{ public_path('images/ika_logo.svg') }}"
                     alt="IKA CRM SYSTEM Logo"
                     style="width:110px;">
            </td>
            <td class="meta">
                <table>
                    @foreach($companyMeta as $label => $value)
                        <tr>
                            <td style="width:70px;"><strong>{{ $label }}:</strong></td>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    {{-- ---------- Başlık + Tarih ---------- --}}
    <h2>Firma Raporu
        @isset($range) ({{ $range[0] }} – {{ $range[1] }}) @endisset
    </h2>
    <p class="date">Oluşturulma: {{ $now }}</p>
    <hr>

    {{-- ---------- İçerik Tablosu ---------- --}}
    <table>
        <thead>
            <tr>
                <th>Firma Adı</th>
                <th>Vergi No</th>
                <th>Telefon</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>Kayıt Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $c)
                <tr>
                    <td>{{ $c->company_name }}</td>
                    <td>{{ $c->tax_number }}</td>
                    <td>{{ $c->phone_number }}</td>
                    <td>{{ $c->email }}</td>
                    <td>
                        @switch($c->current_role)
                            @case('customer')  Müşteri   @break
                            @case('supplier')  Tedarikçi @break
                            @case('candidate') Aday      @break
                            @default           Belirtilmemiş
                        @endswitch
                    </td>
                    <td>{{ \Carbon\Carbon::parse($c->registration_date)->format('d.m.Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ---------- Alt Bilgi ---------- --}}
    <footer>
        Sayfa <span class="pno"></span>
    </footer>

</body>
</html>
