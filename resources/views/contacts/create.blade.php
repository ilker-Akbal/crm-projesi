@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Add Contact</h3></div>
   <form action="{{ route('contacts.store') }}" method="POST">
    @csrf
    <div class="card-body">
     @include('partials.alerts')
     <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="position">Position</label>
          <input type="text" name="position" id="position" class="form-control" value="{{ old('position') }}">
        </div>
      </div>
     </div>
     <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
        </div>
      </div>
     </div>
     <div class="form-group">
        <label for="company_id">Company</label>
        <select name="company_id" id="company_id" class="form-control">
          <option value="">-- select --</option>
          @foreach($companies as $c)
            <option value="{{ $c->id }}" {{ old('company_id')==$c->id ? 'selected' : '' }}>{{ $c->company_name }}</option>
          @endforeach
        </select>
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
