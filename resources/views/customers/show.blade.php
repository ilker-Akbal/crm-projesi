{{-- resources/views/admin/customers/show.blade.php --}}
@extends('layouts.app')

@section('title','Müşteri Detayları')

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
  .btn-glass{
    border: none;
    border-radius: 10px;
    padding: .55rem 1rem;
    font-weight: 700;
    transition: transform .12s ease, box-shadow .12s ease;
    text-decoration: none;
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
  .btn-glass:hover{ transform: translateY(-1px); }
</style>
@endpush

@section('content')
<div class="container py-3" style="max-width: 800px;">

  {{-- Üst başlık --}}
  <div class="hero d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
      <div class="hero-title">Müşteri Detayları</div>
      <div class="hero-sub">{{ $customer->customer_name }}</div>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="btn-glass btn-secondary-grad">
      ← Listeye Geri Dön
    </a>
  </div>

  {{-- Detay kartı --}}
  <div class="glass-card">
    <p><strong>ID:</strong> {{ $customer->id }}</p>
    <p><strong>Ad:</strong> {{ $customer->customer_name }}</p>
    <p><strong>Tip:</strong> {{ $customer->customer_type }}</p>
    <p><strong>Telefon:</strong> {{ $customer->phone }}</p>
    <p><strong>E-posta:</strong> {{ $customer->email }}</p>
    <p><strong>Adres:</strong> {{ $customer->address }}</p>
  </div>

</div>
@endsection
