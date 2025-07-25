@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    {{-- Başlık kartı --}}
    <div class="card card-outline card-primary mb-3">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title mb-0">Seri Numaraları</h3>
      </div>
    </div>

    {{-- Tablo --}}
    <div class="card card-outline card-primary">
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead class="text-center">
            <tr>
              <th class="text-end">#</th>
              <th>Ürün</th>
              <th>Seri No</th>
              <th>Durum</th>
              <th>Sipariş</th>
              <th>İşlem</th>
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
                <td>
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

                <td>
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
