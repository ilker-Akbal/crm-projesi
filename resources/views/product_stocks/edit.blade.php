@extends('layouts.app')
@section('content')
<section class="content"><div class="container-fluid">
  <div class="card card-outline card-primary">
    <div class="card-header"><h3 class="card-title">Stok Düzenle #{{ $productStock->id }}</h3></div>
    <form action="{{ route('product_stocks.update', $productStock) }}" method="POST">
      @csrf @method('PUT')
      <div class="card-body">
        {{-- Ürün seçimi --}}
        <div class="form-group">
          <label>Ürün *</label>
          <select name="product_id" class="form-control" required>
            @foreach($products as $p)
              <option value="{{ $p->id }}"
                {{ old('product_id',$productStock->product_id)==$p->id?'selected':'' }}>
                {{ $p->product_name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-row">
          <div class="col-md-4">
            <label>Toplam Stok *</label>
            <input type="number" name="stock_quantity" class="form-control"
                   value="{{ old('stock_quantity',$productStock->stock_quantity) }}"
                   min="0" required>
          </div>
          <div class="col-md-4">
            <label>Bloke Stok</label>
            <input type="number" name="blocked_stock" class="form-control"
                   value="{{ old('blocked_stock',$productStock->blocked_stock) }}" min="0">
          </div>
          <div class="col-md-4">
            <label>Rezerve Stok</label>
            <input type="number" name="reserved_stock" class="form-control"
                   value="{{ old('reserved_stock',$productStock->reserved_stock) }}" min="0">
          </div>
        </div>

        <div class="form-group mt-3">
  <label>Güncelleme Tarihi *</label>

  @php
    // Veriyi Carbon ile parse et, input formatına getir
    $formattedDateTime = \Carbon\Carbon::parse($productStock->update_date)->format('Y-m-d\TH:i');
  @endphp

  <input type="datetime-local" name="update_date" class="form-control" 
         value="{{ old('update_date', $formattedDateTime) }}" required>

  <small class="text-muted d-block mt-1">
    Blade formatlı değer: <code>{{ $formattedDateTime }}</code><br>
    Orijinal veri (DB): <code>{{ $productStock->update_date }}</code>
  </small>
</div>
      </div>
      <div class="card-footer text-right">
        <a href="{{ route('product_stocks.index') }}" class="btn btn-secondary mr-2">İptal</a>
        <button type="submit" class="btn btn-primary">Güncelle</button>
      </div>
    </form>
  </div>
</div></section>
@endsection
