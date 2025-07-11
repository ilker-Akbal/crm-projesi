@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">

      <div class="card-header"><h3 class="card-title">Cari Hesap Aç</h3></div>

      <form action="{{ route('accounts.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="customer_id">Müşteri</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
              <option value="">-- seçiniz --</option>
              @foreach ($customers as $c)
                <option value="{{ $c->id }}" {{ old('customer_id')==$c->id ? 'selected' : '' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="balance">Açılış Bakiyesi</label>
            <input type="number" step="0.01" name="balance" id="balance"
                   class="form-control" value="{{ old('balance', 0) }}" required>
          </div>

          <div class="form-group">
            <label for="opening_date">Açılış Tarihi</label>
            <input type="date" name="opening_date" id="opening_date"
                   class="form-control" value="{{ old('opening_date', today()->toDateString()) }}">
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
