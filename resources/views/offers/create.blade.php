@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Offer</h3></div>
      <form action="{{ route('offers.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          {{-- Giriş yapan kullanıcının customer_id bilgisi --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="order_id">Order (optional)</label>
                <select name="order_id" id="order_id" class="form-control">
                  <option value="">-- select --</option>
                  @foreach($orders as $o)
                    <option value="{{ $o->id }}" {{ old('order_id')==$o->id?'selected':'' }}>
                      #{{ $o->id }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="offer_date">Offer Date</label>
                <input type="date" name="offer_date" id="offer_date"
                  class="form-control" value="{{ old('offer_date',today()->toDateString()) }}">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="valid_until">Valid Until</label>
                <input type="date" name="valid_until" id="valid_until"
                  class="form-control" value="{{ old('valid_until') }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                  @foreach(['hazırlanıyor','gönderildi','kabul','reddedildi'] as $st)
                    <option value="{{ $st }}" {{ old('status')==$st?'selected':'' }}>
                      {{ ucfirst($st) }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount"
              class="form-control" value="{{ old('total_amount',0) }}">
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('offers.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
