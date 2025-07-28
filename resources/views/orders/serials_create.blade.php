@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Seri No Gir: Sipariş #{{ $order->id }} - {{ $product->product_name }}</h3>
      </div>

      <form action="{{ route('orders.serials.store', $order) }}" method="POST">
        @csrf
        <div class="card-body">
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <input type="hidden" name="qty" value="{{ $qty }}">

          <p>Bu ürün için <strong>{{ $qty }}</strong> adet seri numarası giriniz:</p>

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

        </div><!-- /.card-body -->

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary mr-2">Geri</a>
          <button type="submit" class="btn btn-success">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
