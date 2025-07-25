@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Destek Talebi Oluştur</h3>
      </div>

      <form action="{{ route('support.store') }}" method="POST">
        @csrf

        {{-- gizli customer_id --}}
        <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

        <div class="card-body">
          <div class="form-group">
  <label for="title">Konu Başlığı *</label>
  <select name="title"
          id="title"
          class="form-control @error('title') is-invalid @enderror"
          required>
    <option value="" disabled {{ old('title') ? '' : 'selected' }}>-- Konu seçiniz --</option>
    <option value="Teknik Destek" {{ old('title') == 'Teknik Destek' ? 'selected' : '' }}>Teknik Destek</option>
    <option value="Faturalama ve Ödeme" {{ old('title') == 'Faturalama ve Ödeme' ? 'selected' : '' }}>Faturalama ve Ödeme</option>
  </select>
  @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation"
                      id="explanation"
                      rows="4"
                      class="form-control @error('explanation') is-invalid @enderror">{{ old('explanation') }}</textarea>
            @error('explanation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="situation">Durum *</label>
            <select name="situation"
                    id="situation"
                    class="form-control @error('situation') is-invalid @enderror"
                    required>
              <option value="pending"  {{ old('situation')=='pending'  ? 'selected':'' }}>Beklemede</option>
              <option value="resolved" {{ old('situation')=='resolved' ? 'selected':'' }}>Çözüldü</option>
            </select>
            @error('situation') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="registration_date">Kayıt Tarihi *</label>
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
          <a href="{{ route('support.index') }}" class="btn btn-secondary me-2">İptal</a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
