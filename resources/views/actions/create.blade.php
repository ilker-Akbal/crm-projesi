@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">New Action</h3></div>
      <form action="{{ route('actions.store') }}" method="POST">
        @csrf
        <div class="card-body">

          {{-- Giriş yapan kullanıcının customer_id ve user_id bilgileri --}}
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">
          <input type="hidden" name="user_id" value="{{ auth()->id() }}">

          {{-- Action Type (Dropdown) --}}
          <div class="form-group">
            <label for="action_type">Type</label>
            <select name="action_type" id="action_type" class="form-control" required>
              <option value="" disabled {{ old('action_type') ? '' : 'selected' }}>-- Tür Seçiniz --</option>
              <option value="meeting"  {{ old('action_type') == 'meeting' ? 'selected' : '' }}>Toplantı</option>
              <option value="call"     {{ old('action_type') == 'call' ? 'selected' : '' }}>Telefon</option>
              <option value="email"    {{ old('action_type') == 'email' ? 'selected' : '' }}>E-posta</option>
              <option value="visit"    {{ old('action_type') == 'visit' ? 'selected' : '' }}>Ziyaret</option>
              <option value="other"    {{ old('action_type') == 'other' ? 'selected' : '' }}>Diğer</option>
            </select>
          </div>

          {{-- Action Date --}}
          <div class="form-group">
            <label for="action_date">Date</label>
            <input type="date" name="action_date" id="action_date"
                   class="form-control"
                   value="{{ old('action_date', today()->toDateString()) }}"
                   required>
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
