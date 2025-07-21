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

          <div class="form-group">
            <label for="action_type">Type</label>
            <input name="action_type" id="action_type" class="form-control" value="{{ old('action_type') }}" required>
          </div>

          <div class="form-group">
            <label for="action_date">Date</label>
            <input type="date" name="action_date" id="action_date"
                   class="form-control" value="{{ old('action_date', today()->toDateString()) }}" required>
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
