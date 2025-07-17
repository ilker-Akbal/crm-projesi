@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Create Support Request</h3>
      </div>

      <form action="{{ route('support.store') }}" method="POST">
        @csrf

        {{-- gizli customer_id --}}
        <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

        <div class="card-body">
          <div class="form-group">
            <label for="title">Title *</label>
            <input name="title"
                   id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}"
                   required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation"
                      id="explanation"
                      rows="4"
                      class="form-control @error('explanation') is-invalid @enderror">{{ old('explanation') }}</textarea>
            @error('explanation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="situation">Status *</label>
            <select name="situation"
                    id="situation"
                    class="form-control @error('situation') is-invalid @enderror"
                    required>
              <option value="pending"  {{ old('situation')=='pending'  ? 'selected':'' }}>Pending</option>
              <option value="resolved" {{ old('situation')=='resolved' ? 'selected':'' }}>Resolved</option>
            </select>
            @error('situation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="registration_date">Registration Date *</label>
            <input type="date"
                   name="registration_date"
                   id="registration_date"
                   class="form-control @error('registration_date') is-invalid @enderror"
                   value="{{ old('registration_date', today()->toDateString()) }}"
                   required>
            @error('registration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('support.index') }}" class="btn btn-secondary me-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
