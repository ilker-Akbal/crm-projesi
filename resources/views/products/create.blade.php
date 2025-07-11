@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header"><h3 class="card-title">Ürün Ekle</h3></div>

      <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="product_name">Ürün Adı</label>
            <input type="text" name="product_name" id="product_name" class="form-control"
                   value="{{ old('product_name') }}" required>
          </div>

          <div class="form-group">
            <label for="customer_id">Bağlı Müşteri (opsiyonel)</label>
            <select name="customer_id" id="customer_id" class="form-control">
              <option value="">-- seçiniz --</option>
              @foreach ($customers as $c)
                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation" id="explanation" rows="3" class="form-control">{{ old('explanation') }}</textarea>
          </div>
        </div>

        <div class="card-footer">
          @include('partials.form-buttons')
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
