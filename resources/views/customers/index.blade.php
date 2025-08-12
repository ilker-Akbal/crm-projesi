{{-- resources/views/admin/customers/index.blade.php --}}
@extends('layouts.app')

@section('title','Müşteriler')

@push('styles')
<style>
  :root {
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

  body {
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.12), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.10), transparent 60%),
      var(--bg);
  }

  .hero {
    position: relative;
    border-radius: 20px;
    padding: 20px;
    background:
      linear-gradient(120deg, rgba(124,58,237,.12), rgba(6,182,212,.10)),
      var(--panel);
    border: 1px solid var(--stroke);
    box-shadow: var(--shadow);
    margin-bottom: 18px;
  }
  .hero-title { font-weight: 800; font-size: clamp(1.2rem, 2.2vw, 1.6rem); display: flex; align-items: center; gap: 10px; }
  .hero-title i { color: var(--primary); }
  .hero-sub { color: var(--muted) }

  .btn-glass {
    border: 1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    height: 42px;
    padding: 0 .9rem;
    border-radius: 12px;
    display: flex; align-items: center; gap: 8px;
    font-weight: 700;
    box-shadow: var(--shadow);
    text-decoration: none !important;
  }
  .btn-primary-grad {
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    color: #fff; border: none;
  }
  .btn-danger-grad {
    background: linear-gradient(135deg, var(--danger), #b5179e);
    color: #fff; border: none;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 10px;
    margin-top: 15px;
  }
  .stat-box {
    background: var(--panel);
    border: 1px solid var(--stroke);
    border-radius: 12px;
    padding: 14px;
    box-shadow: var(--shadow);
    text-align: center;
  }
  .stat-title { font-size: .8rem; color: var(--muted); display: flex; align-items: center; justify-content: center; gap: 6px; }
  .stat-title i { color: var(--primary); }
  .stat-value { font-weight: bold; font-size: 1.3rem; margin-top: 4px; }

  .glass-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
  }
  .glass-table thead th {
    color: var(--muted);
    font-weight: 800;
    padding: 10px 14px;
  }
  .glass-row {
    background: var(--panel);
    border: 1px solid var(--stroke);
    border-radius: 16px;
    box-shadow: var(--shadow);
  }
  .glass-row td {
    padding: 14px;
    vertical-align: middle;
  }
  .actions { display: flex; gap: 8px; flex-wrap: wrap; }

  @media (max-width: 768px) {
    .table-wrap { display: none; }
    .cards { display: grid; gap: 10px; }
  }
  @media (min-width: 769px) {
    .cards { display: none; }
  }
</style>
@endpush

@section('content')
<div class="container py-3">

  {{-- Üst Başlık --}}
  <div class="hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <div>
        <div class="hero-title"><i class="fas fa-users"></i> Müşteriler</div>
        <div class="hero-sub">Müşteri kayıtlarını görüntüleyin, düzenleyin ve yönetin.</div>
      </div>
      <div class="toolbar-right d-flex gap-2">
        <a href="{{ url('/admin') }}" class="btn-glass">
          <i class="fas fa-arrow-left"></i> Geri
        </a>
        <a href="{{ route('admin.customers.create') }}" class="btn-glass btn-primary-grad">
          <i class="fas fa-user-plus"></i> Yeni Müşteri Ekle
        </a>
      </div>
    </div>

    {{-- İstatistik Kutuları --}}
    <div class="stats-grid">
      <div class="stat-box">
        <div class="stat-title"><i class="fas fa-database"></i> Toplam Müşteri</div>
        <div class="stat-value">{{ $customers->count() }}</div>
      </div>
      <div class="stat-box">
        <div class="stat-title"><i class="fas fa-calendar-week"></i> Son 7 Günde Eklenen</div>
        <div class="stat-value">{{ $last7 ?? 0 }}</div>
      </div>
    </div>
  </div>

  {{-- Masaüstü Tablo --}}
  <div class="table-wrap">
    <table class="glass-table">
      <thead>
        <tr>
          <th><i class="fas fa-id-badge"></i> Ad</th>
          <th><i class="fas fa-tag"></i> Tür</th>
          <th><i class="fas fa-envelope"></i> E-posta</th>
          <th><i class="fas fa-clock"></i> Son Güncelleme</th>
          <th style="width:260px">İşlemler</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
          <tr class="glass-row">
            <td>{{ $customer->customer_name }}</td>
            <td>
              @php
                $types = [
                  'supplier' => 'Tedarikçi',
                  'candidate' => 'Aday',
                  'manager' => 'Yönetici'
                ];
              @endphp
              {{ $types[$customer->customer_type] ?? ucfirst($customer->customer_type) }}
            </td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->updated_at ? $customer->updated_at->diffForHumans() : '-' }}</td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn-glass">
                  <i class="fas fa-eye"></i> Görüntüle
                </a>
                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-glass">
                  <i class="fas fa-edit"></i> Düzenle
                </a>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-glass btn-danger-grad"
                          onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')">
                    <i class="fas fa-trash-alt"></i> Sil
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr class="glass-row">
            <td colspan="5" class="text-center">Kayıtlı müşteri bulunamadı.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Mobil Kartlar --}}
  <div class="cards">
    @forelse($customers as $customer)
      <div class="card-item">
        <strong>{{ $customer->customer_name }}</strong> - {{ $types[$customer->customer_type] ?? ucfirst($customer->customer_type) }}
        <div style="color: var(--muted)">{{ $customer->email }}</div>
        <small>Güncellendi: {{ $customer->updated_at ? $customer->updated_at->diffForHumans() : '-' }}</small>
        <div class="actions mt-2">
          <a href="{{ route('admin.customers.show', $customer) }}" class="btn-glass">
            <i class="fas fa-eye"></i>
          </a>
          <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-glass">
            <i class="fas fa-edit"></i>
          </a>
          <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-glass btn-danger-grad"
                    onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')">
              <i class="fas fa-trash-alt"></i>
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="card-item text-center">Kayıtlı müşteri bulunamadı.</div>
    @endforelse
  </div>

  {{-- Sayfalama --}}
  @if(method_exists($customers,'links'))
    <div class="mt-3">
      {{ $customers->links() }}
    </div>
  @endif

</div>
@endsection
