@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
  
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Hatırlatıcı Ekle</h3></div>
      <form action="{{ route('reminders.store') }}" method="POST">
        @csrf

        {{-- gizli alanlarla otomatik atama --}}
        <input type="hidden" name="user_id"     value="{{ auth()->id() }}">
        <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

        <div class="card-body">
          <div class="form-group">
            <label for="title">Başlık *</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}"
                   required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="reminder_date">Hatırlatma Tarihi *</label>
            <input type="date"
                   name="reminder_date"
                   id="reminder_date"
                   class="form-control @error('reminder_date') is-invalid @enderror"
                   value="{{ old('reminder_date', today()->toDateString()) }}"
                   required>
            @error('reminder_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation"
                      id="explanation"
                      rows="3"
                      class="form-control @error('explanation') is-invalid @enderror"
            >{{ old('explanation') }}</textarea>
            @error('explanation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('reminders.index') }}" class="btn btn-secondary me-2">
            İptal
          </a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
