@extends('layouts.app')

@push('styles')
<style>
  .table {
    table-layout: fixed; /* Sabit sütun hizası sağlar */
    width: 100%;
  }
  .table th, .table td {
    vertical-align: middle;
  }
  /* Ürün ve Seri No sütunlarını sola hizala */
  .table th:nth-child(2),
  .table td:nth-child(2),
  .table th:nth-child(3),
  .table td:nth-child(3) {
    text-align: left;
  }
  /* Badge ve buton hizası */
  .badge, .btn {
    line-height: 1.2 !important;
    display: inline-block;
    vertical-align: middle;
  }
</style>
@endpush

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Başlık kartı --}}
    <div class="card card-outline card-primary mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Seri Numaraları</h3>
        {{-- Arama formu --}}
        <form action="{{ route('product_serials.index') }}" method="GET" class="d-flex" style="gap: 5px;">
          <input type="text" name="q" class="form-control form-control-sm"
                 placeholder="Ara..." value="{{ request('q') }}">
          <button class="btn btn-sm btn-primary">Ara</button>
        </form>
      </div>
    </div>

    {{-- Tablo --}}
    <div class="card card-outline card-primary">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="text-center">
            <tr>
              <th class="text-end" style="width: 60px;">#</th>
              <th style="width: 25%;">Ürün</th>
              <th style="width: 25%;">Seri No</th>
              <th style="width: 120px;">Durum</th>
              <th style="width: 120px;">Sipariş</th>
              <th style="width: 100px;">İşlem</th>
            </tr>
          </thead>
          <tbody>
            @forelse($serials as $s)
              @php
                $badge = $s->status === 'sold'     ? 'success'
                       : ($s->status === 'reserved' ? 'warning' : 'secondary');
              @endphp
              <tr>
                <td class="text-end">{{ $s->id }}</td>
                <td>{{ $s->product->product_name }}</td>
                <td>{{ $s->serial_number }}</td>

                {{-- Durum rozeti --}}
                <td class="text-center">
                  <span class="badge badge-{{ $badge }}">{{ ucfirst($s->status) }}</span>
                </td>

                {{-- Sipariş kolonunda varsa link göster --}}
                <td class="text-center">
                  @if($s->order_id)
                    <a href="{{ route('orders.show', $s->order_id) }}"
                       class="badge badge-info">#{{ $s->order_id }}</a>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>

                <td class="text-center">
                  <form method="POST"
                        action="{{ route('product_serials.destroy', $s) }}"
                        onsubmit="return confirm('Silinsin mi?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Sil</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Kayıt yok</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
@endsection
