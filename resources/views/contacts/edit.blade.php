@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Contact #{{ $contact->id }}</h3>
      </div>
      <form action="{{ route('contacts.update',$contact) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          @include('partials.alerts')
          
          <div class="form-group">
            <label for="company_id">Company</label>
            <select name="company_id" id="company_id" class="form-control">
              <option value="">-- select --</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}" {{ old('company_id',$contact->company_id)==$c->id?'selected':'' }}>
                  {{ $c->company_name }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="form-group">
            <label for="name">Name *</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name',$contact->name) }}" required>
          </div>
          
          <div class="form-group">
            <label for="position">Position</label>
            <input type="text" name="position" id="position" class="form-control"
                   value="{{ old('position',$contact->position) }}">
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email',$contact->email) }}">
          </div>
          
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="{{ old('phone',$contact->phone) }}">
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('contacts.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
