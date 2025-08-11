{{-- resources/views/admin/customers/index.blade.php --}}
@extends('layouts.app')

@section('title','Müşteriler')

@push('styles')
<style>
  /* ——— Yalnızca AÇIK tema değişkenleri ——— */
  :root{
    --bg: #f8fafc;                 /* arka plan */
    --panel: rgba(255,255,255,.92);/* cam panel */
    --stroke: rgba(15,23,42,.08);  /* sınır çizgisi */
    --text: #1f2937;               /* metin */
    --muted: #4b5563;              /* ikincil metin */
    --primary: #7c3aed;            /* vurgu */
    --primary-2: #06b6d4;          /* vurgu 2 */
    --danger: #f43f5e;
    --warning: #f59e0b;
    --shadow: 0 12px 32px rgba(15, 23, 42, .10);
  }

  /* Arka plan + yumuşak gradyanlar */
  body{
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.12), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.10), transparent 60%),
      var(--bg);
  }

  /* Hero üst şerit */
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
  .hero:before{
    content:""; position:absolute; inset:-1px; border-radius: 20px; padding:1px;
    background: linear-gradient(135deg, rgba(124,58,237,.35), rgba(6,182,212,.35), transparent 70%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude; pointer-events:none;
  }
  .hero-title{ font-weight: 800; font-size: clamp(1.2rem, 2.2vw, 1.6rem); letter-spacing:.2px }
  .hero-sub{ color: var(--muted) }

  .toolbar{
    display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between;
    margin-top: 10px;
  }
  .toolbar-left, .toolbar-right{ display:flex; gap:10px; flex-wrap:wrap; align-items:center; }

  .btn-glass{
    border:1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    height: 42px;
    padding: 0 .9rem;
    border-radius: 12px;
    display:flex; align-items:center; gap:8px;
    font-weight:700;
    box-shadow: var(--shadow);
    text-decoration: none !important;
    transition: transform .12s ease, box-shadow .12s ease;
  }
  .btn-glass:hover{ transform: translateY(-1px); }
  .btn-primary-grad{
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    color:#fff; border:none;
  }
  .btn-danger-grad{
    background: linear-gradient(135deg, var(--danger), #b5179e);
    color:#fff; border:none;
  }

  .search-input{
    border:1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    height: 42px;
    border-radius: 12px;
    padding: 0 .9rem;
    outline: none;
    min-width: 260px;
    box-shadow: var(--shadow);
  }
  .search-input::placeholder{ color: var(--muted); }

  /* Cam tablo */
  .glass-table{
    width:100%;
    border-collapse: separate;
    border-spacing: 0 10px; /* satırlar arası boşluk */
  }
  .glass-table thead th{
    color: var(--muted);
    font-weight: 800;
    border: none;
    padding: 10px 14px;
  }
  .glass-row{
    background: var(--panel);
    border:1px solid var(--stroke);
    color: var(--text);
    border-radius: 16px;
    box-shadow: var(--shadow);
  }
  .glass-row td{
    padding: 14px;
    vertical-align: middle;
    border: none;
  }
  .actions{ display:flex; gap:8px; flex-wrap:wrap; }

  /* Responsive kart görünümü */
  @media (max-width: 768px){
    .table-wrap{ display:none; }
    .cards{ display:grid; grid-template-columns: 1fr; gap:10px; }
  }
  @media (min-width: 769px){
    .cards{ display:none; }
  }
  .card-item{
    background: var(--panel);
    border:1px solid var(--stroke);
    color: var(--text);
    border-radius: 16px;
    padding: 14px;
    box-shadow: var(--shadow);
  }
  .card-top{ display:flex; justify-content:space-between; align-items:center; gap:8px; }
  .chip{
    font-size:.72rem; padding:.25rem .55rem; border-radius: 999px;
    background: rgba(124,58,237,.14); color:#5b21b6; border:1px solid rgba(124,58,237,.25);
  }

  /* Ripple (tıklama dalga efekti) */
  .ripple{ position:absolute; border-radius:50%; transform: scale(0); pointer-events:none;
    background: radial-gradient(circle, rgba(255,255,255,.6) 0%, rgba(255,255,255,.25) 40%, transparent 70%);
    animation: ripple .6s ease-out forwards;
  }
  @keyframes ripple{ to{ transform: scale(12); opacity:0; } }
</style>
@endpush

@section('content')
<div class="container py-3">

  {{-- Üst başlık --}}
  <div class="hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <div>
        <div class="hero-title">Müşteriler</div>
        <div class="hero-sub">Müşteri kayıtlarını görüntüleyin, düzenleyin ve yönetin.</div>
      </div>
      <div class="toolbar-right">
        <a href="{{ url('/admin') }}" class="btn-glass">
          <i class="fas fa-arrow-left"></i> Geri
        </a>
        <a href="{{ route('admin.customers.create') }}" class="btn-glass btn-primary-grad ripple-src">
          <i class="fas fa-user-plus"></i> Yeni Müşteri Ekle
        </a>
      </div>
    </div>

    {{-- Arama/Form araçları --}}
    <div class="toolbar">
      <div class="toolbar-left">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex gap-2 align-items-center">
          <input type="text" name="q" value="{{ request('q') }}" class="search-input" placeholder="Ada, e-postaya göre ara...">
          <button type="submit" class="btn-glass">
            <i class="fas fa-search"></i> Ara
          </button>
          @if(request()->has('q') && request('q')!=='')
            <a href="{{ route('admin.customers.index') }}" class="btn-glass btn-danger-grad">
              <i class="fas fa-times"></i> Temizle
            </a>
          @endif
        </form>
      </div>
      <div class="toolbar-right">
        @isset($customers)
          <span class="chip">
            <i class="fas fa-database me-1"></i>
            {{ method_exists($customers,'total') ? $customers->total() : $customers->count() }} kayıt
          </span>
        @endisset
      </div>
    </div>
  </div>

  {{-- Masaüstü tablo --}}
  <div class="table-wrap">
    <table class="glass-table">
      <thead>
        <tr>
          <th>Ad</th>
          <th>Tür</th>
          <th>E-posta</th>
          <th style="width:260px">İşlemler</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
          <tr class="glass-row">
            <td>{{ $customer->customer_name }}</td>
            <td>{{ ucfirst($customer->customer_type) }}</td>
            <td>{{ $customer->email }}</td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn-glass ripple-src">
                  <i class="fas fa-eye"></i> Görüntüle
                </a>
                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-glass ripple-src">
                  <i class="fas fa-edit"></i> Düzenle
                </a>
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-glass btn-danger-grad ripple-src"
                          onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')">
                    <i class="fas fa-trash-alt"></i> Sil
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr class="glass-row">
            <td colspan="4" class="text-center" style="padding:24px">Kayıtlı müşteri bulunamadı.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Mobil kartlar --}}
  <div class="cards">
    @forelse($customers as $customer)
      <div class="card-item position-relative">
        <div class="card-top">
          <strong>{{ $customer->customer_name }}</strong>
          <span class="chip">{{ ucfirst($customer->customer_type) }}</span>
        </div>
        <div class="mt-1" style="color:var(--muted)">{{ $customer->email }}</div>

        <div class="actions mt-3">
          <a href="{{ route('admin.customers.show', $customer) }}" class="btn-glass ripple-src">
            <i class="fas fa-eye"></i> Görüntüle
          </a>
          <a href="{{ route('admin.customers.edit', $customer) }}" class="btn-glass ripple-src">
            <i class="fas fa-edit"></i> Düzenle
          </a>
          <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-glass btn-danger-grad ripple-src"
                    onclick="return confirm('Bu müşteriyi silmek istediğinize emin misiniz?')">
              <i class="fas fa-trash-alt"></i> Sil
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
      {{ $customers->withQueryString()->links() }}
    </div>
  @endif

</div>
@endsection

@push('scripts')
<script>
  // Ripple efekti
  document.querySelectorAll('.ripple-src').forEach(el => {
    el.addEventListener('click', function(e){
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const ripple = document.createElement('span');
      ripple.className = 'ripple';
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
      ripple.style.top  = (e.clientY - rect.top  - size/2) + 'px';
      this.style.position = 'relative';
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 650);
    });
  });
</script>
@endpush
