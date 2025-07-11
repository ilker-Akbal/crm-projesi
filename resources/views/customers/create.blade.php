@extends('layouts.app')
@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Add Company</h3></div>
   <form action="{{ route('companies.store') }}" method="POST">
    @csrf
    <div class="card-body">
     @include('partials.alerts')
     <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="company_name">Company Name</label>
          <input type="text" name="company_name" id="company_name" class="form-control" required value="{{ old('company_name') }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="tax_number">Tax Number</label>
          <input type="text" name="tax_number" id="tax_number" class="form-control" value="{{ old('tax_number') }}">
        </div>
      </div>
     </div>
     <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="phone_number">Phone</label>
          <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
        </div>
      </div>
     </div>
     <div class="form-group">
        <label for="address">Address</label>
        <textarea name="address" id="address" class="form-control" rows="2">{{ old('address') }}</textarea>
     </div>
     <div class="form-group">
        <label for="customer_id">Belongs to Customer</label>
        <select name="customer_id" id="customer_id" class="form-control">
          <option value="">-- select --</option>
          @foreach($customers as $cust)
            <option value="{{ $cust->id }}" {{ old('customer_id')==$cust->id ? 'selected' : '' }}>{{ $cust->customer_name }}</option>
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