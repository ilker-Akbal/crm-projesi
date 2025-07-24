@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Kişi Düzenle #{{ $contact->id }}</h3>
      </div>
      <form action="{{ route('contacts.update',$contact) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">

          {{-- Firma Seçimi --}}
          <div class="form-group">
            <label for="company_id">Firma</label>
            <select name="company_id" id="company_id" class="form-control">
              <option value="">-- seçiniz --</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}" {{ old('company_id',$contact->company_id)==$c->id ? 'selected' : '' }}>
                  {{ $c->company_name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Ad --}}
          <div class="form-group">
            <label for="name">Ad *</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name',$contact->name) }}" required>
          </div>

          {{-- Pozisyon (dropdown) --}}
          <div class="form-group">
            <label for="position">Pozisyon</label>
            <select name="position" id="position" class="form-control">
              <option value="">-- seçiniz --</option>

              {{-- Yönetim ve Ofis --}}
              <option value="Genel Müdür" {{ old('position',$contact->position)=='Genel Müdür' ? 'selected' : '' }}>Genel Müdür</option>
              <option value="Müdür" {{ old('position',$contact->position)=='Müdür' ? 'selected' : '' }}>Müdür</option>
              <option value="Yönetici" {{ old('position',$contact->position)=='Yönetici' ? 'selected' : '' }}>Yönetici</option>
              <option value="Proje Yöneticisi" {{ old('position',$contact->position)=='Proje Yöneticisi' ? 'selected' : '' }}>Proje Yöneticisi</option>
              <option value="Sekreter" {{ old('position',$contact->position)=='Sekreter' ? 'selected' : '' }}>Sekreter</option>

              {{-- Muhasebe & İnsan Kaynakları --}}
              <option value="Muhasebeci" {{ old('position',$contact->position)=='Muhasebeci' ? 'selected' : '' }}>Muhasebeci</option>
              <option value="İnsan Kaynakları Uzmanı" {{ old('position',$contact->position)=='İnsan Kaynakları Uzmanı' ? 'selected' : '' }}>İnsan Kaynakları Uzmanı</option>
              <option value="Finans Uzmanı" {{ old('position',$contact->position)=='Finans Uzmanı' ? 'selected' : '' }}>Finans Uzmanı</option>

              {{-- Satış & Pazarlama --}}
              <option value="Satış Uzmanı" {{ old('position',$contact->position)=='Satış Uzmanı' ? 'selected' : '' }}>Satış Uzmanı</option>
              <option value="Pazarlama Uzmanı" {{ old('position',$contact->position)=='Pazarlama Uzmanı' ? 'selected' : '' }}>Pazarlama Uzmanı</option>
              <option value="Müşteri Temsilcisi" {{ old('position',$contact->position)=='Müşteri Temsilcisi' ? 'selected' : '' }}>Müşteri Temsilcisi</option>

              {{-- Teknik Departmanlar (HAUS örnekleri) --}}
              <option value="Bilgi İşlem Uzmanı" {{ old('position',$contact->position)=='Bilgi İşlem Uzmanı' ? 'selected' : '' }}>Bilgi İşlem Uzmanı</option>
              <option value="IT Destek" {{ old('position',$contact->position)=='IT Destek' ? 'selected' : '' }}>IT Destek</option>
              <option value="Yazılım Geliştirici" {{ old('position',$contact->position)=='Yazılım Geliştirici' ? 'selected' : '' }}>Yazılım Geliştirici</option>
              <option value="Üretim Mühendisi" {{ old('position',$contact->position)=='Üretim Mühendisi' ? 'selected' : '' }}>Üretim Mühendisi</option>
              <option value="Makine Operatörü" {{ old('position',$contact->position)=='Makine Operatörü' ? 'selected' : '' }}>Makine Operatörü</option>
              <option value="Bakım Teknisyeni" {{ old('position',$contact->position)=='Bakım Teknisyeni' ? 'selected' : '' }}>Bakım Teknisyeni</option>
              <option value="Kalite Kontrol Uzmanı" {{ old('position',$contact->position)=='Kalite Kontrol Uzmanı' ? 'selected' : '' }}>Kalite Kontrol Uzmanı</option>
              <option value="Ar-Ge Mühendisi" {{ old('position',$contact->position)=='Ar-Ge Mühendisi' ? 'selected' : '' }}>Ar-Ge Mühendisi</option>
              <option value="Lojistik Sorumlusu" {{ old('position',$contact->position)=='Lojistik Sorumlusu' ? 'selected' : '' }}>Lojistik Sorumlusu</option>
              <option value="Depo Sorumlusu" {{ old('position',$contact->position)=='Depo Sorumlusu' ? 'selected' : '' }}>Depo Sorumlusu</option>

              {{-- Diğer --}}
              <option value="Stajyer" {{ old('position',$contact->position)=='Stajyer' ? 'selected' : '' }}>Stajyer</option>
              <option value="Diğer" {{ old('position',$contact->position)=='Diğer' ? 'selected' : '' }}>Diğer</option>
            </select>
          </div>

          {{-- E-posta --}}
          <div class="form-group">
            <label for="email">E-posta</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email',$contact->email) }}">
          </div>

          {{-- Telefon --}}
          <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="{{ old('phone',$contact->phone) }}">
          </div>

        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('contacts.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
