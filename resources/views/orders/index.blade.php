@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Siparişler</h3>
        <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary">Yeni Sipariş Ekle</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Müşteri</th>
              <th>Sipariş Tarihi</th>
              <th>Teslim Tarihi</th>
              <th>Ödeme Durumu</th>
              <th>Toplam</th>
              <th>İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td>{{ $o->customer->customer_name }}</td>
              <td>{{ $o->order_date }}</td>
              <td>{{ $o->delivery_date }}</td>
              <td>
                {{ $o->is_paid
                      ? '✓ Ödendi ('.optional($o->paid_at)->format('d.m.Y').')'
                      : 'Bekliyor' }}
              </td>
              <td>{{ number_format($o->total_amount,2) }}</td>
              <td>
                <a href="{{ route('orders.show',$o) }}" class="btn btn-sm btn-info">Göster</a>
                <a href="{{ route('orders.edit',$o) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('orders.destroy',$o) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Herhangi bir sipariş bulunamadı.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
