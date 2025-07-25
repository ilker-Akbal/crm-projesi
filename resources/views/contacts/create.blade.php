@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Kişi Ekle</h3>
      </div>
      <form action="{{ route('contacts.store') }}" method="POST">
        @csrf
        <div class="card-body">

          {{-- Hata Mesajları --}}
          @if($errors->any())
            <div class="alert alert-danger">
              <strong>Formda hatalar var:</strong>
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Firma Seçimi --}}
          <div class="form-group">
            <label for="company_id">Firma</label>
            <select name="company_id" id="company_id" class="form-control">
              <option value="">-- seçiniz --</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}" {{ old('company_id')==$c->id ? 'selected' : '' }}>
                  {{ $c->company_name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Ad --}}
          <div class="form-group">
            <label for="name">Ad *</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name') }}" required>
          </div>

          {{-- Pozisyon (dropdown, HAUS tarzı departmanlar eklendi) --}}
          <div class="form-group">
            <label for="position">Pozisyon</label>
            <select name="position" id="position" class="form-control">
              <option value="">-- seçiniz --</option>

              {{-- Yönetim ve Ofis --}}
              <option value="Genel Müdür" {{ old('position')=='Genel Müdür' ? 'selected' : '' }}>Genel Müdür</option>
              <option value="Müdür" {{ old('position')=='Müdür' ? 'selected' : '' }}>Müdür</option>
              <option value="Yönetici" {{ old('position')=='Yönetici' ? 'selected' : '' }}>Yönetici</option>
              <option value="Proje Yöneticisi" {{ old('position')=='Proje Yöneticisi' ? 'selected' : '' }}>Proje Yöneticisi</option>
              <option value="Sekreter" {{ old('position')=='Sekreter' ? 'selected' : '' }}>Sekreter</option>

              {{-- Muhasebe & İnsan Kaynakları --}}
              <option value="Muhasebeci" {{ old('position')=='Muhasebeci' ? 'selected' : '' }}>Muhasebeci</option>
              <option value="İnsan Kaynakları Uzmanı" {{ old('position')=='İnsan Kaynakları Uzmanı' ? 'selected' : '' }}>İnsan Kaynakları Uzmanı</option>
              <option value="Finans Uzmanı" {{ old('position')=='Finans Uzmanı' ? 'selected' : '' }}>Finans Uzmanı</option>

              {{-- Satış & Pazarlama --}}
              <option value="Satış Uzmanı" {{ old('position')=='Satış Uzmanı' ? 'selected' : '' }}>Satış Uzmanı</option>
              <option value="Pazarlama Uzmanı" {{ old('position')=='Pazarlama Uzmanı' ? 'selected' : '' }}>Pazarlama Uzmanı</option>
              <option value="Müşteri Temsilcisi" {{ old('position')=='Müşteri Temsilcisi' ? 'selected' : '' }}>Müşteri Temsilcisi</option>

              {{-- Teknik Departmanlar --}}
              <option value="Bilgi İşlem Uzmanı" {{ old('position')=='Bilgi İşlem Uzmanı' ? 'selected' : '' }}>Bilgi İşlem Uzmanı</option>
              <option value="IT Destek" {{ old('position')=='IT Destek' ? 'selected' : '' }}>IT Destek</option>
              <option value="Yazılım Geliştirici" {{ old('position')=='Yazılım Geliştirici' ? 'selected' : '' }}>Yazılım Geliştirici</option>
              <option value="Üretim Mühendisi" {{ old('position')=='Üretim Mühendisi' ? 'selected' : '' }}>Üretim Mühendisi</option>
              <option value="Makine Operatörü" {{ old('position')=='Makine Operatörü' ? 'selected' : '' }}>Makine Operatörü</option>
              <option value="Bakım Teknisyeni" {{ old('position')=='Bakım Teknisyeni' ? 'selected' : '' }}>Bakım Teknisyeni</option>
              <option value="Kalite Kontrol Uzmanı" {{ old('position')=='Kalite Kontrol Uzmanı' ? 'selected' : '' }}>Kalite Kontrol Uzmanı</option>
              <option value="Ar-Ge Mühendisi" {{ old('position')=='Ar-Ge Mühendisi' ? 'selected' : '' }}>Ar-Ge Mühendisi</option>
              <option value="Lojistik Sorumlusu" {{ old('position')=='Lojistik Sorumlusu' ? 'selected' : '' }}>Lojistik Sorumlusu</option>
              <option value="Depo Sorumlusu" {{ old('position')=='Depo Sorumlusu' ? 'selected' : '' }}>Depo Sorumlusu</option>

              {{-- Diğer --}}
              <option value="Stajyer" {{ old('position')=='Stajyer' ? 'selected' : '' }}>Stajyer</option>
              <option value="Diğer" {{ old('position')=='Diğer' ? 'selected' : '' }}>Diğer</option>
            </select>
          </div>

          {{-- E-posta --}}
          <div class="form-group">
            <label for="email">E-posta</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email') }}">
          </div>

          {{-- Telefon --}}
          <div class="form-group">
            <label for="phone">Telefon</label>
            <input
              type="text"
              name="phone"
              id="phone"
              class="form-control @error('phone') is-invalid @enderror"
              value="{{ old('phone') }}"
              maxlength="11"
              pattern="\d{11}"
              inputmode="numeric"
              required
            >
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('contacts.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
