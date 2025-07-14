@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Customer</h2>

    {{-- genel validasyon / flash --}}
    @include('partials.alerts')

    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT') {{-- HTML formu PUT yapamıyor, spoof --}}
        
        {{-- Name --}}
        <div class="form-group">
            <label for="customer_name">Name *</label>
            <input  type="text"
                    name="customer_name"
                    id="customer_name"
                    class="form-control @error('customer_name') is-invalid @enderror"
                    value="{{ old('customer_name', $customer->customer_name) }}"
                    required>
            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Type --}}
        <div class="form-group">
            <label for="customer_type">Type *</label>
            <select name="customer_type"
                    id="customer_type"
                    class="form-control @error('customer_type') is-invalid @enderror"
                    required>
                @php $types = ['customer' => 'Customer', 'supplier' => 'Supplier', 'candidate' => 'Candidate']; @endphp
                @foreach ($types as $value => $label)
                    <option value="{{ $value }}"
                            {{ old('customer_type', $customer->customer_type) === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('customer_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Phone --}}
        <div class="form-group">
            <label for="phone">Phone</label>
            <input  type="text"
                    name="phone"
                    id="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $customer->phone) }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Email</label>
            <input  type="email"
                    name="email"
                    id="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $customer->email) }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Address --}}
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address"
                      id="address"
                      rows="3"
                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Buttons --}}
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
