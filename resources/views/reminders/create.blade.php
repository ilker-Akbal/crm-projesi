@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Reminder</h3></div>

      <div class="card-body">
        {{-- Demo form – şimdilik kayıt yapmaz --}}
        <form>
          <div class="form-group">
            <label>Title</label>
            <input type="text" class="form-control" placeholder="Reminder title">
          </div>

          <div class="form-group">
            <label>Reminder Date</label>
            <input type="date" class="form-control">
          </div>

          <div class="form-group">
            <label>Customer</label>
            <select class="form-control">
              <option value="">-- select --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>User</label>
            <select class="form-control">
              <option value="">-- select --</option>
              @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->username }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Explanation</label>
            <textarea class="form-control" rows="3" placeholder="…"></textarea>
          </div>

          <button type="submit" class="btn btn-primary">Kaydet (demo)</button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
