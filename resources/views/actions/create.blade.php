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
          <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
              <option value="">-- select --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
              <option value="">-- select --</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id')==$u->id?'selected':'' }}>
                  {{ $u->username }}
                </option>
              @endforeach
            </select>
          </div>
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
