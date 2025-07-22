@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">@lang('messages.open_account')</h3>
      </div>

      <form action="{{ route('accounts.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          {{-- Otomatik olarak giriş yapan kullanıcının customer_id bilgisi --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          <div class="form-group">
            <label for="balance">@lang('messages.balance') *</label>
            <input  type="number" step="0.01" name="balance" id="balance"
                    class="form-control"
                    value="{{ old('balance', 0) }}" required
                    placeholder="{{ __('messages.balance_placeholder') }}">
          </div>

          <div class="form-group">
            <label for="opening_date">@lang('messages.opening_date') *</label>
            <input  type="date" name="opening_date" id="opening_date"
                    class="form-control"
                    value="{{ old('opening_date', today()->toDateString()) }}" required>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('accounts.index') }}" class="btn btn-secondary mr-2">
            @lang('messages.cancel')
          </a>
          <button type="submit" class="btn btn-primary">
            @lang('messages.save')
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
