@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px;">
  <div class="card shadow rounded-lg">
    <div class="card-header bg-warning text-white">
      <h3 class="card-title mb-0">Müşteri Düzenle</h3>
    </div>
    <div class="card-body">
      

      <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
          <label for="customer_name" class="form-label">Ad *</label>
          <input type="text" name="customer_name" id="customer_name"
                 class="form-control @error('customer_name') is-invalid @enderror"
                 value="{{ old('customer_name', $customer->customer_name) }}" required>
          @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="customer_type" class="form-label">Tür *</label>
          <select name="customer_type" id="customer_type"
                  class="form-select @error('customer_type') is-invalid @enderror" required>
            @foreach(['customer'=>'Müşteri','supplier'=>'Tedarikçi','candidate'=>'Aday'] as $val => $lbl)
              <option value="{{ $val }}"
                      {{ old('customer_type', $customer->customer_type) == $val ? 'selected' : '' }}>
                {{ $lbl }}
              </option>
            @endforeach
          </select>
          @error('customer_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Telefon</label>
            <input type="text" name="phone" id="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $customer->phone) }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $customer->email) }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="mb-4">
          <label for="address" class="form-label">Adres</label>
          <textarea name="address" id="address" rows="3"
                    class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>
          @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-warning me-2">Güncelle</button>
          <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">İptal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
