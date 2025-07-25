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

      <form action="{{ route('orders.serials.store', $order) }}" method="POST">
        @csrf
        {{-- Ürün id gizli --}}
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="card-body">

          {{-- Uyarı bandı --}}
          <div class="alert alert-warning py-2 mb-3">
            Bu seri numaraları
            <span class="badge badge-info">SİPARİŞ #{{ $order->id }}</span>
            için <strong>rezerve</strong> edilecek.
            Ödeme tamamlandığında stoktan düşecektir.
          </div>

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
          @php
            $remain = $order->products()->whereDoesntHave('serials')->count();
          @endphp
          <button type="submit" class="btn btn-primary">
            Kaydet ve {{ $remain === 1 ? 'Bitir' : 'Devam Et' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
