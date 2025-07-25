@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Hesap Düzenle #{{ $account->id }}</h3>
      </div>

      <form action="{{ route('accounts.update', $account) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          

          {{-- Otomatik olarak giriş yapan kullanıcının customer_id bilgisi --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="form-group">
            <label for="balance">Bakiye *</label>
            <input type="number" step="0.01" name="balance" id="balance"
                   class="form-control"
                   value="{{ old('balance', $account->balance) }}" required>
          </div>

          <div class="form-group">
            <label for="opening_date">Açılış Tarihi *</label>
            <input type="date" name="opening_date" id="opening_date"
                   class="form-control"
                   value="{{ old('opening_date', $account->opening_date) }}" required>
          </div>
          
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('accounts.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
