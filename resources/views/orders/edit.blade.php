@extends('layouts.app')

@section('title', 'Sipariş Düzenle #' . $order->id)

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Sipariş Düzenle #{{ $order->id }}</h3>
      </div>

      <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
          

          {{-- Giriş yapan kullanıcının customer_id’si --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          {{-- Sipariş Türü --}}
          <div class="form-group">
            <label>Sipariş Türü *</label><br>
            <label class="mr-3">
              <input type="radio" name="order_type" value="sale"
                     {{ old('order_type', $order->order_type) == 'sale' ? 'checked' : '' }}>
              Satış
            </label>

            <label>
              <input type="radio" name="order_type" value="purchase"
                     {{ old('order_type', $order->order_type) == 'purchase' ? 'checked' : '' }}>
              Satın Alma
            </label>

            @error('order_type')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          {{-- Sipariş Tarihi --}}
          <div class="form-group">
            <label for="order_date">Sipariş Tarihi</label>
            <input
              type="date"
              name="order_date"
              id="order_date"
              class="form-control @error('order_date') is-invalid @enderror"
              value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}"
              required
            >
            @error('order_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Teslim Tarihi --}}
          <div class="form-group">
            <label for="delivery_date">Teslim Tarihi</label>
            <input
              type="date"
              name="delivery_date"
              id="delivery_date"
              class="form-control @error('delivery_date') is-invalid @enderror"
              value="{{ old('delivery_date', optional($order->delivery_date) ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '') }}"
            >
            @error('delivery_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <hr>

          {{-- Sipariş Kalemleri --}}
          <h5>Sipariş Kalemleri</h5>
          <div id="items-container">
            @php
              $oldItems = old('items', $order->products->map(function($p) {
                return [
                  'product_id' => $p->id,
                  'amount'     => $p->pivot->amount,
                  'unit_price' => $p->pivot->unit_price,
                ];
              })->toArray());
            @endphp

            @forelse($oldItems as $i => $item)
              <div class="row mb-2 item-row">
                <div class="col-md-5">
                  <select name="items[{{ $i }}][product_id]" class="form-control">
                    <option value="">-- Ürün Seçiniz --</option>
                    @foreach($products as $prod)
                      <option value="{{ $prod->id }}"
                        {{ $item['product_id'] == $prod->id ? 'selected' : '' }}>
                        {{ $prod->product_name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][amount]"
                         class="form-control" min="1"
                         value="{{ $item['amount'] }}">
                </div>
                <div class="col-md-3">
                  <input type="number" name="items[{{ $i }}][unit_price]"
                         class="form-control" min="0" step="0.01"
                         value="{{ $item['unit_price'] }}">
                </div>
                <div class="col-md-1">
                  <button type="button" class="btn btn-danger remove-item">&times;</button>
                </div>
              </div>
            @empty
              {{-- Kalem yok --}}
            @endforelse
          </div>

          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">
            + Kalem Ekle
          </button>

          {{-- Ödeme durumu checkbox --}}
          <div class="form-group mt-3">
            <label>
              <input type="checkbox" name="is_paid" value="1"
                     {{ old('is_paid', $order->is_paid) ? 'checked' : '' }}>
              Ödeme tamamlandı
            </label>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('orders.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Siparişi Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Kalem satırı ekle/sil
  document.getElementById('add-item').addEventListener('click', () => {
    const container = document.getElementById('items-container');
    const index = container.querySelectorAll('.item-row').length;
    const row = document.createElement('div');
    row.classList.add('row', 'mb-2', 'item-row');
    row.innerHTML = `
      <div class="col-md-5">
        <select name="items[${index}][product_id]" class="form-control">
          <option value="">-- Ürün Seçiniz --</option>
          @foreach($products as $prod)
            <option value="{{ $prod->id }}">{{ $prod->product_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="items[${index}][amount]" class="form-control" min="1">
      </div>
      <div class="col-md-3">
        <input type="number" name="items[${index}][unit_price]" class="form-control" min="0" step="0.01">
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-danger remove-item">&times;</button>
      </div>
    `;
    container.appendChild(row);
  });

  document.addEventListener('click', (e) => {
    if (e.target.matches('.remove-item')) {
      e.target.closest('.item-row').remove();
    }
  });
</script>
@endpush
