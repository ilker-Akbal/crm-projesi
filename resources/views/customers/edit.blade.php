{{-- resources/views/admin/customers/edit.blade.php --}}
@extends('layouts.app')

@section('title','Müşteri Düzenle')

@push('styles')
<style>
  :root{
    --bg: #f8fafc;
    --panel: rgba(255,255,255,.92);
    --stroke: rgba(15,23,42,.08);
    --text: #1f2937;
    --muted: #4b5563;
    --primary: #7c3aed;
    --primary-2: #06b6d4;
    --danger: #f43f5e;
    --shadow: 0 12px 32px rgba(15, 23, 42, .10);
  }
  body{
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.12), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.10), transparent 60%),
      var(--bg);
  }
  .hero{
    position: relative; border-radius: 20px; padding: 20px;
    color: var(--text);
    background:
      linear-gradient(120deg, rgba(124,58,237,.12), rgba(6,182,212,.10)),
      var(--panel);
    border: 1px solid var(--stroke);
    box-shadow: var(--shadow);
    margin-bottom: 18px;
  }
  .hero-title{ font-weight: 800; font-size: 1.5rem }
  .hero-sub{ color: var(--muted) }

  .glass-card{
    background: var(--panel);
    border:1px solid var(--stroke);
    border-radius: 16px;
    padding: 20px;
    box-shadow: var(--shadow);
  }
  label{ font-weight: 600; color: var(--text); }
  .form-control, .form-select, textarea{
    border: 1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    border-radius: 10px;
    box-shadow: var(--shadow);
  }
  .form-control:focus, .form-select:focus, textarea:focus{
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(124,58,237,.25);
  }
  .btn-glass{
    border: none;
    border-radius: 10px;
    padding: .55rem 1rem;
    font-weight: 700;
    transition: transform .12s ease, box-shadow .12s ease;
  }
  .btn-primary-grad{
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    color:#fff;
    box-shadow: 0 6px 18px rgba(124,58,237,.25);
  }
  .btn-secondary-grad{
    background: linear-gradient(135deg, #94a3b8, #64748b);
    color:#fff;
    box-shadow: 0 6px 18px rgba(100,116,139,.25);
  }
  .btn-danger-grad{
    background: linear-gradient(135deg, var(--danger), #b5179e);
    color:#fff;
    box-shadow: 0 6px 18px rgba(245,23,120,.25);
  }
  .btn-glass:hover{ transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="container py-3" style="max-width: 900px;">

  {{-- Üst başlık / toolbar --}}
  <div class="hero d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
      <div class="hero-title">Müşteri Düzenle</div>
      <div class="hero-sub">Zorunlu alanlar * ile işaretlenmiştir.</div>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.customers.index') }}" class="btn-glass btn-secondary-grad">
        ← Geri
      </a>
    </div>
  </div>

  {{-- Hata kutusu --}}
  @if ($errors->any())
    <div class="glass-card mb-3">
      <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li style="color:#b91c1c">{{ $error }}</li>
      @endforeach
      </ul>
    </div>
  @endif

  {{-- Form kartı --}}
  <div class="glass-card">
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
      @csrf @method('PUT')

      <div class="mb-3">
        <label for="customer_name" class="form-label">Ad *</label>
        <input type="text"
               name="customer_name"
               id="customer_name"
               pattern="[a-zA-ZçÇğĞıİöÖşŞüÜ\s]+"
               title="Müşteri adı sadece harf ve boşluk içerebilir"
               class="form-control @error('customer_name') is-invalid @enderror"
               value="{{ old('customer_name', $customer->customer_name) }}"
               required>
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
          <input type="text"
                 name="phone"
                 id="phone"
                 pattern="\d{11}"
                 maxlength="11"
                 title="Telefon numarası 11 haneli olmalıdır"
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

      <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn-glass btn-primary-grad">
          Güncelle
        </button>
        <a href="{{ route('admin.customers.index') }}" class="btn-glass btn-secondary-grad">
          İptal
        </a>
      </div>

    </form>
  </div>
</div>
@endsection
