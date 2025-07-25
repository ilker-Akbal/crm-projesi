@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">
          Seri Numaraları — {{ $product->product_name }}
        </h3>
      </div>

      <form action="{{ route('products.serials.store', $product) }}" method="POST">
        @csrf

        {{-- Siparişten geldiyse gizli order_id --}}
        @if(!empty($order))
          <input type="hidden" name="order_id" value="{{ $order->id }}">
        @endif

        <div class="card-body">

          {{-- Uyarı bandı --}}
          @if(!empty($order))
            <div class="alert alert-warning mb-3 py-2">
              <strong>Dikkat:</strong> Bu seri numaraları
              <span class="badge badge-info">SİPARİŞ #{{ $order->id }}</span>
              için <em>rezerve</em> edilecek. Ödeme tamamlandığında stoktan düşecektir.
            </div>
          @else
            <div class="alert alert-secondary mb-3 py-2">
              Bu seri numaraları <em>serbest stok</em> olarak eklenecek.
            </div>
          @endif

          <p>
            Girilmesi gereken adet:
            <span class="badge badge-primary badge-pill px-2">{{ $qty }}</span>
          </p>
          <hr>

          @for ($i = 0; $i < $qty; $i++)
            <div class="form-group">
              <label>Seri No #{{ $i + 1 }}</label>
              <input  type="text"
                      name="serials[]"
                      class="form-control @error('serials.'.$i) is-invalid @enderror"
                      value="{{ old('serials.'.$i) }}"
                      required>
              @error('serials.'.$i)
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          @endfor
        </div>

        <div class="card-footer text-right">
          <button type="submit" class="btn btn-primary">
            Kaydet ve Bitir
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
