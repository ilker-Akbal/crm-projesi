@php $now = \Carbon\Carbon::now()->format('d.m.Y H:i'); @endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Satış Raporu</title>
  <style>
    @page { margin:25px 20px 55px 20px; }
    body   { font-family:DejaVu Sans,sans-serif; font-size:11px; margin:0; }
    h2     { margin:6px 0 0; font-size:16px; text-align:center; }
    .date  { font-size:10px; text-align:center; color:#555; margin-bottom:8px; }
    hr     { border:none; border-top:1px solid #777; margin:6px 0 10px; }

    /* Üst bant */
    .logo  { width:110px; }
    .info-table td { font-size:10px; padding:1px 3px; }

    /* Grafik Konteyner */
    .graph-container { 
      margin:20px 0 10px;
      border:1px solid #e0e0e0;
      border-radius:4px;
      padding:5px;
      background:#fff;
      box-shadow:0 1px 3px rgba(0,0,0,0.1);
      width:100%;
      height:450px;
      position:relative;
    }
    .graph-inner {
      height:100%;
      width:100%;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .graph-img {
      width:100%;
      height:100%;
      object-fit:contain;
    }

    /* Tablo */
    table{ border-collapse:collapse; width:100%; }
    th,td{ border:0.5pt solid #000; padding:4px; text-align:left; }
    thead tr{ background:#f3f3f3; }
    tbody tr:nth-child(even){ background:#fafafa; }

    /* Footer */
    footer{ 
      position:fixed;
      bottom:-30px;
      left:0;
      right:0;
      text-align:center;
      font-size:9px;
      color:#555;
    }
    footer .pno::after{ content:counter(page); }
  </style>
</head>
<body>

  <!-- Üst bant -->
  <table style="width:100%; margin-bottom:8px">
    <tr>
      <td style="width:40%"><img src="{{ $logoData }}" class="logo" alt="Logo"></td>
      <td>
        <table class="info-table">
          <tr>
            <td style="width:80px"><strong>Adres:</strong></td>
            <td>{{ $companyInfo['address'] }}</td>
          </tr>
          <tr>
            <td><strong>Vergi No:</strong></td>
            <td>{{ $companyInfo['tax'] }}</td>
          </tr>
          <tr>
            <td><strong>Telefon:</strong></td>
            <td>{{ $companyInfo['phone'] }}</td>
          </tr>
          <tr>
            <td><strong>E-posta:</strong></td>
            <td>{{ $companyInfo['email'] }}</td>
          </tr>
          <tr>
            <td><strong>Web:</strong></td>
            <td>{{ $companyInfo['web'] ?? 'www.ikacrm.com' }}</td>
          </tr>
          <tr>
            <td><strong>Instagram:</strong></td>
            <td>{{ $companyInfo['instagram'] ?? '@ikacrm' }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <h2>Satış Raporu</h2>
  <p class="date">Oluşturulma: {{ $now }}</p>
  <hr>

  <!-- Grafik -->
  <div class="graph-container">
    <div class="graph-inner">
      <img src="{{ $revenueUrl }}" class="graph-img" alt="Satış Grafiği">
    </div>
  </div>

  <!-- Sipariş Tablosu -->
  <table>
    <thead>
      <tr>
        <th>Firma</th>
        <th>Ürün(ler)</th>
        <th>Sipariş Tarihi</th>
        <th style="text-align:right">Toplam (₺)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($orders as $o)
        <tr>
          <td>{{ $o->company->company_name ?? '—' }}</td>
          <td>{{ $o->products->pluck('product_name')->implode(', ') }}</td>
          <td>{{ $o->order_date->format('d.m.Y') }}</td>
          <td style="text-align:right">{{ number_format($o->total_amount,2,',','.') }}</td>
        </tr>
      @endforeach
      <tr>
        <td colspan="4" style="text-align:right; font-weight:bold">
          Toplam Ciro: {{ number_format($ordersTotal,2,',','.') }} ₺
        </td>
      </tr>
    </tbody>
  </table>

  <footer>Sayfa <span class="pno"></span></footer>
</body>
</html>
