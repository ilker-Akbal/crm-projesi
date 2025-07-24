@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">
          Seri Numaralarını Gir — {{ $product->product_name }}
        </h3>
    </div>

    <form action="{{ route('orders.serials.store', $order) }}" method="POST">
      @csrf

      {{-- Ürün id gizli --}}
      <input type="hidden" name="product_id" value="{{ $product->id }}">

      <div class="card-body">
        <p>Sipariş Edilen Adet: <strong>{{ $qty }}</strong></p><hr>

        @for ($i = 0; $i < $qty; $i++)
          <div class="form-group">
            <label>Seri No #{{ $i+1 }}</label>
            <input type="text" name="serials[]" class="form-control @error("serials.$i") is-invalid @enderror"
                   value="{{ old("serials.$i") }}" required>
            @error("serials.$i") <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        @endfor
      </div>

      <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary">
          Kaydet ve 
          {{ $order->products()->whereDoesntHave('serials')->count() === 1 ? 'Bitir' : 'Devam Et' }}
        </button>
      </div>
    </form>
  </div>
 </div>
</section>
@endsection
