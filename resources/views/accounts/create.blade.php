@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Open Account</h3></div>
      <form action="{{ route('accounts.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          {{-- Otomatik olarak giriş yapan kullanıcının customer_id bilgisi --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="form-group">
            <label for="balance">Balance *</label>
            <input type="number" step="0.01" name="balance" id="balance"
                   class="form-control" value="{{ old('balance',0) }}" required>
          </div>

          <div class="form-group">
            <label for="opening_date">Opening Date *</label>
            <input type="date" name="opening_date" id="opening_date"
                   class="form-control" 
                   value="{{ old('opening_date',today()->toDateString()) }}" required>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('accounts.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
