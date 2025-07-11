@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header"><h3 class="card-title">Teklif Oluştur</h3></div>

      <form action="{{ route('offers.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="customer_id">Müşteri</label>
                <select name="customer_id" id="customer_id" class="form-control">
                  <option value="">-- seçiniz --</option>
                  @foreach ($customers as $c)
                    <option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>
                      {{ $c->customer_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="order_id">Bağlı Sipariş (opsiyonel)</label>
                <select name="order_id" id="order_id" class="form-control">
                  <option value="">-- seçiniz --</option>
                  @foreach ($orders as $o)
                    <option value="{{ $o->id }}" {{ old('order_id')==$o->id?'selected':'' }}>#{{ $o->id }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="offer_date">Teklif Tarihi</label>
                <input type="date" name="offer_date" id="offer_date" class="form-control"
                       value="{{ old('offer_date', today()->toDateString()) }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label for="valid_until">Geçerlilik</label>
                <input type="date" name="valid_until" id="valid_until" class="form-control"
                       value="{{ old('valid_until') }}">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="status">Durum</label>
            <select name="status" id="status" class="form-control">
              @foreach (['hazırlanıyor','gönderildi','kabul','reddedildi'] as $st)
                <option value="{{ $st }}" {{ old('status')==$st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
              @endforeach
            </select>
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
