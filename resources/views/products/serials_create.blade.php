@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">
          {{ $order 
             ? "Seri No Gir: Sipariş #{$order->id} – {$product->product_name}" 
             : "Yeni Stok için Seri No: {$product->product_name}" }}
        </h3>
      </div>

      <form action="{{ route('products.serials.store', $product) }}" method="POST">
        @csrf

        {{-- Hata Mesajları --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Gizli Alanlar --}}
        <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">
        <input type="hidden" name="expected_qty" value="{{ $added }}">

        <div class="card-body">
          <p>
            @if($order)
              Bu siparişte <strong>{{ $qty }}</strong> adet seri numarası giriniz.
            @else
              Bu ürün için stok artışı <strong>{{ $qty }}</strong> adet seri numarası giriniz.
            @endif
          </p>

          {{-- Seri No Alanları --}}
          @for ($i = 0; $i < $qty; $i++)
            <div class="form-group">
              <label for="serials_{{ $i }}">Seri No #{{ $i + 1 }}</label>
              <input type="text"
                     name="serials[]"
                     id="serials_{{ $i }}"
                     class="form-control @error('serials.' . $i) is-invalid @enderror"
                     value="{{ old('serials.' . $i) }}"
                     required>
              @error('serials.' . $i)
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          @endfor
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ $order ? route('orders.show', $order) : route('products.show', $product) }}"
             class="btn btn-secondary mr-2">Geri</a>
          <button type="submit" class="btn btn-success">
            Kaydet ve Stoğu Güncelle
          </button>
        </div>

      </form>
    </div>
  </div>
</section>
@endsection
