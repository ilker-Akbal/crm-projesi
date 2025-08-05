@extends('layouts.app')
@section('content')
<section class="content"><div class="container-fluid">
  <div class="card card-outline card-primary">
    <div class="card-header"><h3 class="card-title">Yeni Stok</h3></div>
    <form action="{{ route('product_stocks.store') }}" method="POST">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label>Ürün *</label>
          <select name="product_id" class="form-control" required>
            <option value="">-- Seçiniz --</option>
            @foreach($products as $p)
              <option value="{{ $p->id }}">{{ $p->product_name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-row">
          <div class="col"><label>Toplam *</label>
            <input type="number" name="stock_quantity" class="form-control" min="0" required>
          </div>
          <div class="col"><label>Bloke</label>
            <input type="number" name="blocked_stock" class="form-control" min="0">
          </div>
          <div class="col"><label>Rezerve</label>
            <input type="number" name="reserved_stock" class="form-control" min="0">
          </div>
        </div>
        <div class="form-group mt-3">
  <label>Güncelleme Tarihi *</label>
  <input type="datetime-local" name="update_date" class="form-control" 
         value="{{ old('update_date', now()->format('Y-m-d\TH:i')) }}" required>
</div>
      </div>
      <div class="card-footer text-right">
        <a href="{{ route('product_stocks.index') }}" class="btn btn-secondary mr-2">İptal</a>
        <button type="submit" class="btn btn-primary">Kaydet</button>
      </div>
    </form>
  </div>
</div></section>
@endsection
