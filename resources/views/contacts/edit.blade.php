@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Kişi Düzenle #{{ $contact->id }}</h3>
      </div>
      <form action="{{ route('contacts.update', $contact) }}" method="POST">
        @csrf
        @method('PUT')
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
                <option value="{{ $c->id }}" {{ old('company_id', $contact->company_id) == $c->id ? 'selected' : '' }}>
                  {{ $c->company_name }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Ad --}}
          <div class="form-group">
            <label for="name">Ad *</label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $contact->name) }}"
                   pattern="[A-Za-zÇçĞğİıÖöŞşÜü\s]{2,}"
                   title="Ad kısmına sadece harf ve boşluk girilebilir"
                   required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Pozisyon --}}
          <div class="form-group">
            <label for="position">Pozisyon</label>
            <select name="position" id="position" class="form-control">
              <option value="">-- seçiniz --</option>
              @php
                $positions = [
                  'Genel Müdür', 'Müdür', 'Yönetici', 'Proje Yöneticisi', 'Sekreter',
                  'Muhasebeci', 'İnsan Kaynakları Uzmanı', 'Finans Uzmanı',
                  'Satış Uzmanı', 'Pazarlama Uzmanı', 'Müşteri Temsilcisi',
                  'Bilgi İşlem Uzmanı', 'IT Destek', 'Yazılım Geliştirici',
                  'Üretim Mühendisi', 'Makine Operatörü', 'Bakım Teknisyeni',
                  'Kalite Kontrol Uzmanı', 'Ar-Ge Mühendisi',
                  'Lojistik Sorumlusu', 'Depo Sorumlusu',
                  'Stajyer', 'Diğer'
                ];
              @endphp
              @foreach($positions as $p)
                <option value="{{ $p }}" {{ old('position', $contact->position) == $p ? 'selected' : '' }}>{{ $p }}</option>
              @endforeach
            </select>
          </div>

          {{-- E-posta --}}
          <div class="form-group">
            <label for="email">E-posta</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $contact->email) }}">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Telefon --}}
          <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="text"
                   name="phone"
                   id="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $contact->phone) }}"
                   maxlength="11"
                   pattern="\d{11}"
                   inputmode="numeric"
                   required>
            @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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

<script>
  // Ad kısmı – sadece harf ve boşluk
  document.getElementById('name').addEventListener('keypress', function (e) {
    const allowed = /^[A-Za-zÇçĞğİıÖöŞşÜü\s]+$/;
    if (!allowed.test(e.key)) {
      e.preventDefault();
    }
  });

  document.getElementById('name').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^A-Za-zÇçĞğİıÖöŞşÜü\s]/g, '');
  });

  // Telefon kısmı – sadece rakam ve 11 hane sınırı
  document.getElementById('phone').addEventListener('keypress', function (e) {
    if (!/[0-9]/.test(e.key)) {
      e.preventDefault();
    }
  });

  document.getElementById('phone').addEventListener('input', function (e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 11);
  });
</script>

@endsection
