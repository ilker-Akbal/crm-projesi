@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Customer</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="customer_name">Name *</label>
            <input type="text"
                   name="customer_name"
                   id="customer_name"
                   class="form-control @error('customer_name') is-invalid @enderror"
                   value="{{ old('customer_name') }}"
                   required>
            @error('customer_name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="customer_type">Type *</label>
            <select name="customer_type"
                    id="customer_type"
                    class="form-control @error('customer_type') is-invalid @enderror"
                    required>
                <option value="" disabled {{ old('customer_type') ? '' : 'selected' }}>-- select type --</option>
                <option value="customer" {{ old('customer_type')=='customer' ? 'selected' : '' }}>Customer</option>
                <option value="supplier" {{ old('customer_type')=='supplier' ? 'selected' : '' }}>Supplier</option>
                <option value="candidate" {{ old('customer_type')=='candidate' ? 'selected' : '' }}>Candidate</option>
            </select>
            @error('customer_type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text"
                   name="phone"
                   id="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}">
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address"
                      id="address"
                      class="form-control @error('address') is-invalid @enderror"
                      rows="3">{{ old('address') }}</textarea>
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
