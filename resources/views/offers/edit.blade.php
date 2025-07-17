{{-- resources/views/offers/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Offer #' . $offer->id)

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Offer #{{ $offer->id }}</h3>
      </div>

      <form action="{{ route('offers.update', $offer) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
          @include('partials.alerts')

          {{-- Gizli customer_id: oturumlu kullanıcının customer_id’si --}}
          <input type="hidden"
                 name="customer_id"
                 value="{{ auth()->user()->customer_id }}" />

          {{-- Eğer kullanıcıya sipariş seçtirmek isterseniz açın, yoksa yine gizli alan yapabilirsiniz --}}
          <div class="form-group">
            <label for="order_id">Order</label>
            <select name="order_id" id="order_id"
                    class="form-control @error('order_id') is-invalid @enderror">
              <option value="">-- Seçiniz --</option>
              @foreach($orders as $order)
                <option value="{{ $order->id }}"
                  {{ old('order_id', $offer->order_id) == $order->id ? 'selected' : '' }}>
                  #{{ $order->id }} — {{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}
                </option>
              @endforeach
            </select>
            @error('order_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="offer_date">Offer Date</label>
            <input type="date"
                   name="offer_date"
                   id="offer_date"
                   class="form-control @error('offer_date') is-invalid @enderror"
                   value="{{ old('offer_date', $offer->offer_date->format('Y-m-d')) }}"
                   required>
            @error('offer_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="valid_until">Valid Until</label>
            <input type="date"
                   name="valid_until"
                   id="valid_until"
                   class="form-control @error('valid_until') is-invalid @enderror"
                   value="{{ old('valid_until', optional($offer->valid_until)->format('Y-m-d')) }}">
            @error('valid_until')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status"
                    class="form-control @error('status') is-invalid @enderror"
                    required>
              @foreach(['hazırlanıyor'=>'Hazırlanıyor','gönderildi'=>'Gönderildi','kabul'=>'Kabul','reddedildi'=>'Reddedildi'] as $key => $label)
                <option value="{{ $key }}"
                  {{ old('status', $offer->status) === $key ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <hr>

          {{-- Eğer offer ürün satırları da düzenlenecekse: --}}
          <h5>Offer Items</h5>
          <div id="items-container">
            @forelse(old('items', $offer->products->map(function($p) {
              return [
                'product_id'=>$p->id,
                'amount'=>$p->pivot->amount,
                'unit_price'=>$p->pivot->unit_price,
              ];
            })->toArray()) as $i => $item)
            <div class="row mb-2 item-row">
              <div class="col-md-5">
                <select name="items[{{ $i }}][product_id]" class="form-control">
                  <option value="">-- Ürün Seç --</option>
                  @foreach(\App\Models\Product::where('customer_id', auth()->user()->customer_id)->get() as $prod)
                    <option value="{{ $prod->id }}"
                      {{ $item['product_id']==$prod->id ? 'selected' : '' }}>
                      {{ $prod->product_name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <input type="number"
                       name="items[{{ $i }}][amount]"
                       class="form-control"
                       placeholder="Adet"
                       min="1"
                       value="{{ $item['amount'] }}">
              </div>
              <div class="col-md-3">
                <input type="number"
                       name="items[{{ $i }}][unit_price]"
                       class="form-control"
                       placeholder="Birim Fiyat"
                       min="0"
                       step="0.01"
                       value="{{ $item['unit_price'] }}">
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-item">&times;</button>
              </div>
            </div>
            @empty
              {{-- İlk açılışta hiçbir satır yok --}}
            @endforelse
          </div>
          <button type="button" id="add-item" class="btn btn-sm btn-outline-primary">
            + Add Item
          </button>

        </div> {{-- /.card-body --}}

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('offers.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Update Offer</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Basit JS: yeni satır ekleme & silme
  document.getElementById('add-item').addEventListener('click',function(){
    let container = document.getElementById('items-container');
    let index = container.querySelectorAll('.item-row').length;
    let row = document.createElement('div');
    row.classList.add('row','mb-2','item-row');
    row.innerHTML = `
      <div class="col-md-5">
        <select name="items[${index}][product_id]" class="form-control">
          <option value="">-- Ürün Seç --</option>
          @foreach(\App\Models\Product::where('customer_id', auth()->user()->customer_id)->get() as $prod)
            <option value="{{ $prod->id }}">{{ $prod->product_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="items[${index}][amount]" class="form-control" min="1" placeholder="Adet">
      </div>
      <div class="col-md-3">
        <input type="number" name="items[${index}][unit_price]" class="form-control" min="0" step="0.01" placeholder="Birim Fiyat">
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-danger remove-item">&times;</button>
      </div>
    `;
    container.appendChild(row);
  });

  document.addEventListener('click',function(e){
    if(e.target.matches('.remove-item')){
      e.target.closest('.item-row').remove();
    }
  });
</script>
@endpush
