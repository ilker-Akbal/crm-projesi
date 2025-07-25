@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Yeni İşlem</h3>
      </div>

      <form action="{{ route('actions.store') }}" method="POST">
        @csrf
        <div class="card-body">
          <input type="hidden" name="customer_id" value="{{ auth()->user()->customer_id }}">

          {{-- İşlemi Yapan Kişi --}}
          <div class="form-group">
            <label for="contact_id">İşlemi Yapan Kişi</label>
            <select name="contact_id" id="contact_id" class="form-control" required>
              <option value="" disabled selected>-- Kişi Seçiniz --</option>
              @foreach($contacts as $c)
                <option value="{{ $c->id }}" {{ old('contact_id') == $c->id ? 'selected' : '' }}>
                  {{ $c->name }} ({{ $c->company?->company_name ?? 'Firma Yok' }})
                </option>
              @endforeach
            </select>
          </div>

          {{-- Tür --}}
          <div class="form-group">
            <label for="action_type">Tür</label>
            <select name="action_type" id="action_type" class="form-control" required>
              <option value="" disabled {{ old('action_type') ? '' : 'selected' }}>-- Tür Seçiniz --</option>
              <option value="meeting" {{ old('action_type')=='meeting' ? 'selected':'' }}>Toplantı</option>
              <option value="call"    {{ old('action_type')=='call' ? 'selected':'' }}>Telefon</option>
              <option value="email"   {{ old('action_type')=='email' ? 'selected':'' }}>E-posta</option>
              <option value="visit"   {{ old('action_type')=='visit' ? 'selected':'' }}>Ziyaret</option>
              <option value="other"   {{ old('action_type')=='other' ? 'selected':'' }}>Diğer</option>
            </select>
          </div>

          {{-- Tarih --}}
          <div class="form-group">
            <label for="action_date">Tarih</label>
            <input type="date" name="action_date" id="action_date"
                   class="form-control"
                   value="{{ old('action_date', today()->toDateString()) }}" required>
          </div>

          {{-- Durum --}}
          <div class="form-group">
            <label for="status">Durum</label>
            <select name="status" id="status" class="form-control" required>
              <option value="potansiyel" {{ old('status')=='potansiyel' ? 'selected':'' }}>Potansiyel</option>
              <option value="açık"       {{ old('status')=='açık' ? 'selected':'' }}>Açık</option>
              <option value="kapalı"     {{ old('status')=='kapalı' ? 'selected':'' }}>Kapalı</option>
              <option value="iptal"      {{ old('status')=='iptal' ? 'selected':'' }}>İptal</option>
            </select>
          </div>

          {{-- Açıklama --}}
          <div class="form-group">
            <label for="description">Açıklama</label>
            <textarea name="description" id="description" rows="3" class="form-control"
                      placeholder="İşlem ile ilgili detay giriniz...">{{ old('description') }}</textarea>
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
