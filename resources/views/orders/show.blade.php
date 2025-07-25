@extends('layouts.app')

@section('content')
<div class="container">

  {{-- ---------------- Sipariş Başlığı ---------------- --}}
  <h2>Sipariş #{{ $order->id }} Detayları</h2>

  <ul class="list-group mb-4">
    <li class="list-group-item">
      <strong>Müşteri:</strong> {{ $order->customer->customer_name }}
    </li>
    <li class="list-group-item">
      <strong>Firma:</strong> {{ $order->company->company_name ?? '—' }}
    </li>
    <li class="list-group-item">
      <strong>Tip:</strong>
      @if($order->order_type === 'sale')
        <span class="badge badge-info">Satış</span>
      @else
        <span class="badge badge-secondary">Alış</span>
      @endif
    </li>
    <li class="list-group-item">
      <strong>Durum:</strong>
      <span class="badge badge-{{ $order->situation === 'tamamlandı' ? 'success' : 'warning' }}">
        {{ ucfirst($order->situation) }}
      </span>
    </li>
    <li class="list-group-item">
      <strong>Sipariş Tarihi:</strong> {{ $order->order_date->format('d.m.Y') }}
    </li>
    <li class="list-group-item">
      <strong>Teslim Tarihi:</strong> {{ $order->delivery_date?->format('d.m.Y') ?? '—' }}
    </li>
    <li class="list-group-item">
      <strong>Ödeme:</strong>
      @if($order->is_paid)
        <span class="badge badge-success">
          Ödendi {{ $order->paid_at?->format('d.m.Y') }}
        </span>
      @else
        <span class="badge badge-warning">Bekliyor</span>
      @endif
    </li>
    <li class="list-group-item">
      <strong>Toplam:</strong> {{ number_format($order->total_amount, 2) }} ₺
    </li>
  </ul>

  {{-- ---------------- Ürünler ---------------- --}}
  <h4>Ürünler</h4>
  <table class="table table-bordered">
    <thead class="text-center">
      <tr>
        <th>Ürün</th>
        <th class="text-end">Miktar</th>
        <th class="text-end">Birim Fiyat</th>
        <th class="text-end">Ara&nbsp;Toplam</th>
        <th>Seri Numaraları</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->orderProducts as $line)
        @php
          /* Bu ürün-siparişe bağlı seri numaraları */
          $serials = $line->product->serials
                       ->where('order_id', $order->id);
        @endphp
        <tr>
          <td>{{ $line->product->product_name }}</td>
          <td class="text-end">{{ $line->amount }}</td>
          <td class="text-end">{{ number_format($line->unit_price, 2) }} ₺</td>
          <td class="text-end">{{ number_format($line->amount * $line->unit_price, 2) }} ₺</td>
          <td>
            @forelse($serials as $sn)
              @php
                $badge = $sn->status === 'sold'     ? 'success'
                       : ($sn->status === 'reserved' ? 'warning' : 'secondary');
              @endphp
              <span class="badge badge-{{ $badge }} mb-1">{{ $sn->serial_number }}</span>
            @empty
              <span class="text-muted">—</span>
            @endforelse
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <a href="{{ route('orders.index') }}" class="btn btn-secondary">Listeye Dön</a>
</div>
@endsection
