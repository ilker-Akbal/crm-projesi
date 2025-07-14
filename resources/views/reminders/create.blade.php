@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Reminder</h3></div>
      <form action="{{ route('reminders.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <div class="form-group">
            <label for="title">title *</label>
            <input type="text" name="title" id="title"
                   class="form-control" value="{{ old('title') }}" required>
          </div>
          <div class="form-group">
            <label for="reminder_date">Reminder Date *</label>
            <input type="date" name="reminder_date" id="reminder_date"
                   class="form-control" value="{{ old('reminder_date', today()->toDateString()) }}" required>
          </div>
          <div class="form-group">
            <label for="customer_id">Customer *</label>
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
            <label for="user_id">User *</label>
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
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation" rows="3"
                      class="form-control">{{ old('explanation') }}</textarea>
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
