@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')
    <div class="card card-outline card-warning">
      <div class="card-header"><h3 class="card-title">Edit Reminder</h3></div>
      <form action="{{ route('reminders.update', $reminder) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" name="title" id="title"
                   class="form-control" value="{{ old('title',$reminder->title) }}" required>
          </div>
          <div class="form-group">
            <label for="reminder_date">Reminder Date *</label>
            <input type="date" name="reminder_date" id="reminder_date"
                   class="form-control" value="{{ old('reminder_date',$reminder->reminder_date) }}" required>
          </div>
          <div class="form-group">
            <label for="customer_id">Customer *</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
              @foreach($customers as $c)
                <option value="{{ $c->id }}" {{ old('customer_id',$reminder->customer_id)==$c->id?'selected':'' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="user_id">User *</label>
            <select name="user_id" id="user_id" class="form-control" required>
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ old('user_id',$reminder->user_id)==$u->id?'selected':'' }}>
                  {{ $u->username }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation" rows="3"
                      class="form-control">{{ old('explanation',$reminder->explanation) }}</textarea>
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
