@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')
    <div class="card card-primary card-outline">
      <div class="card-header"><h3 class="card-title">Create Support Request</h3></div>
      <form action="{{ route('support.store') }}" method="POST">
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
            <label for="title">title</label>
            <input name="title" id="title" class="form-control" value="{{ old('title') }}" required>
          </div>

          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation" rows="4"
                      class="form-control">{{ old('explanation') }}</textarea>
          </div>

          <div class="form-group">
            <label for="situation">Status</label>
            <select name="situation" id="situation" class="form-control">
              <option value="pending" {{ old('situation')=='pending'?'selected':'' }}>Pending</option>
              <option value="resolved" {{ old('situation')=='resolved'?'selected':'' }}>Resolved</option>
            </select>
          </div>

          <div class="form-group">
            <label for="registration_date">Registration Date</label>
            <input type="date" name="registration_date" id="registration_date"
                   class="form-control" value="{{ old('registration_date', today()->toDateString()) }}">
          </div>
        </div>
        <div class="card-footer">
          <button class="btn btn-primary">Save</button>
          <a href="{{ route('support.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
