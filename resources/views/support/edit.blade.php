{{-- resources/views/support/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    @include('partials.alerts')

    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Edit Support Request</h3>
      </div>

      <form action="{{ route('support.update', $support) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          {{-- Customer --}}
          <div class="form-group">
            <label for="customer_id">Customer *</label>
            <select name="customer_id" id="customer_id"
                    class="form-control @error('customer_id') is-invalid @enderror"
                    required>
              <option value="">-- select --</option>
              @foreach($customers as $c)
                <option value="{{ $c->id }}"
                        {{ old('customer_id', $support->customer_id)==$c->id ? 'selected' : '' }}>
                  {{ $c->customer_name }}
                </option>
              @endforeach
            </select>
            @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Title --}}
          <div class="form-group">
            <label for="title">Title *</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $support->title) }}"
                   required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Explanation --}}
          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation"
                      id="explanation"
                      rows="4"
                      class="form-control @error('explanation') is-invalid @enderror">{{ old('explanation', $support->explanation) }}</textarea>
            @error('explanation')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Situation --}}
          <div class="form-group">
            <label for="situation">Status *</label>
            <select name="situation"
                    id="situation"
                    class="form-control @error('situation') is-invalid @enderror"
                    required>
              <option value="pending"  {{ old('situation', $support->situation)=='pending'  ? 'selected' : '' }}>Pending</option>
              <option value="resolved" {{ old('situation', $support->situation)=='resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            @error('situation')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          {{-- Registration Date --}}
          <div class="form-group">
            <label for="registration_date">Registration Date *</label>
            <input type="date"
                   name="registration_date"
                   id="registration_date"
                   class="form-control @error('registration_date') is-invalid @enderror"
                   value="{{ old('registration_date', $support->registration_date) }}"
                   required>
            @error('registration_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('support.index') }}" class="btn btn-secondary me-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
