{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title','Yönetim Paneli')

@push('styles')
<style>
  :root{
    --bg: #0f172a;               /* slate-900 */
    --panel: rgba(15, 23, 42, .55);
    --stroke: rgba(255,255,255,.12);
    --text: #e2e8f0;             /* slate-200 */
    --muted: #94a3b8;            /* slate-400 */
    --primary: #7c3aed;          /* violet-600 */
    --primary-2: #06b6d4;        /* cyan-500 */
    --danger: #f43f5e;           /* rose-500 */
    --ok: #10b981;               /* emerald-500 */
    --warning: #f59e0b;          /* amber-500 */
  }
  [data-theme="light"]{
    --bg: #f8fafc;               /* slate-50 */
    --panel: rgba(255,255,255,.9);
    --stroke: rgba(15,23,42,.08);
    --text: #1f2937;             /* gray-800 */
    --muted: #4b5563;            /* gray-600 */
  }

  /* Arka plan */
  body{
    background:
      radial-gradient(1200px 600px at -10% -20%, rgba(124,58,237,.25), transparent 60%),
      radial-gradient(1000px 600px at 110% 10%, rgba(6,182,212,.18), transparent 60%),
      var(--bg);
  }

  /* Üst hero */
  .hero{
    position: relative; border-radius: 20px; padding: 24px;
    color: var(--text);
    background:
      linear-gradient(120deg, rgba(124,58,237,.22), rgba(6,182,212,.18)),
      var(--panel);
    border: 1px solid var(--stroke);
    box-shadow: 0 20px 50px rgba(0,0,0,.18);
  }
  .hero:before{
    content:""; position:absolute; inset:-1px; border-radius: 20px; padding:1px;
    background: linear-gradient(135deg, rgba(124,58,237,.6), rgba(6,182,212,.6), transparent 70%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude; pointer-events:none;
  }
  .hero-title{ font-weight: 800; letter-spacing:.2px; font-size: clamp(1.3rem, 2.2vw, 1.8rem); }
  .hero-sub{ color: var(--muted); font-size:1rem }

  .divider{ height:1px; background: linear-gradient(90deg, transparent, var(--stroke), transparent); margin: 16px 0; }

  /* Üst sağ butonlar (Tema + Çıkış) */
  .header-buttons{
    display:flex; align-items:center; gap:12px;
  }
  .theme-toggle,
  .btn-glass{
    height: 42px;               /* aynı yükseklik */
    display:flex; align-items:center; gap:8px;
    padding: 0 .9rem; font-weight:700;
    border-radius: 12px;
    border:1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    box-shadow: 0 6px 18px rgba(0,0,0,.12);
    transition: transform .15s ease, box-shadow .15s ease;
  }
  .theme-toggle:hover,
  .btn-glass:hover{ transform: translateY(-1px); }
  .btn-danger-grad{
    border:none;
    background: linear-gradient(135deg, var(--danger), #b5179e);
    color:#fff;
    box-shadow: 0 8px 24px rgba(245, 23, 120, .25);
  }

  /* KPI grid */
  .kpi-grid{
    display:grid; grid-template-columns: repeat(12, 1fr); gap: 14px; margin-top: 12px;
  }
  @media (max-width: 991.98px){ .kpi-grid{ grid-template-columns: repeat(6, 1fr); } }
  @media (max-width: 575.98px){ .kpi-grid{ grid-template-columns: repeat(2, 1fr); } }

  /* BÜYÜTÜLMÜŞ KPI kartları */
  .kpi{
    grid-column: span 3;
    background: var(--panel); border:1px solid var(--stroke);
    border-radius: 18px; padding: 22px;
    color: var(--text);
    display:flex; gap:16px; align-items:center;
    position:relative; overflow:hidden;
    min-height: 100px; /* daha yüksek */
  }
  .kpi .icon{
    width: 56px; height:56px; border-radius: 14px; display:grid; place-items:center;
    background: linear-gradient(135deg, rgba(124,58,237,.18), rgba(6,182,212,.18));
    border:1px dashed rgba(255,255,255,.25);
  }
  .kpi .icon i{
    font-size: 1.45rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    -webkit-background-clip:text; background-clip:text; color: transparent;
  }
  .kpi .vals .label{ font-size:.85rem; color: var(--muted); font-weight:600 }
  .kpi .vals .val{ font-size:1.7rem; font-weight:900; letter-spacing:.3px }

  /* Tooltip */
  [data-tip]{ position:relative; }
  [data-tip]::after{
    content: attr(data-tip);
    position:absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%);
    white-space: nowrap; background: rgba(0,0,0,.75); color:#fff; font-size:.75rem;
    padding:.25rem .5rem; border-radius: 6px; opacity:0; pointer-events:none; transition:.15s ease; z-index:10;
  }
  [data-tip]:hover::after{ opacity:1; }

  /* Kart ızgarası */
  .card-grid{
    display:grid; grid-template-columns: repeat(12, 1fr); gap: 18px; margin-top: 18px;
  }
  @media (max-width: 991.98px){ .card-grid{ grid-template-columns: repeat(6, 1fr); } }
  @media (max-width: 575.98px){ .card-grid{ grid-template-columns: repeat(2, 1fr); } }

  /* Ana cam kartlar */
  .glass-card{
    grid-column: span 6;
    background: var(--panel);
    border:1px solid var(--stroke);
    border-radius: 16px;
    padding: 20px 18px;
    color: var(--text);
    text-decoration:none !important;
    display:flex; align-items:center; gap:14px;
    position: relative; overflow:hidden;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .glass-card:hover{ transform: translateY(-3px); border-color: rgba(124,58,237,.35); box-shadow: 0 18px 40px rgba(124,58,237,.15); }
  .glass-card:focus-visible{ outline: 3px solid rgba(6,182,212,.55); outline-offset: 3px; }

  .icon-wrap{
    width:58px; height:58px; flex:0 0 auto; border-radius: 14px; display:grid; place-items:center;
    background: linear-gradient(135deg, rgba(124,58,237,.22), rgba(6,182,212,.22));
    border:1px dashed rgba(255,255,255,.25);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.06);
    transition: transform .2s ease;
  }
  .glass-card:hover .icon-wrap{ transform: rotate(3deg) scale(1.03); }
  .icon-wrap i{
    font-size: 1.45rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-2));
    -webkit-background-clip:text; background-clip:text; color: transparent;
  }

  .card-titles h3{ margin:0; font-size: 1.1rem; font-weight: 800 }
  .card-titles p{ margin:2px 0 0; font-size:.95rem; color: var(--muted) }

  .chev{ margin-left:auto; opacity:.6; transition: transform .2s ease, opacity .2s ease; }
  .glass-card:hover .chev{ transform: translateX(3px); opacity:.9; }

  /* Ripple */
  .ripple{
    position:absolute; border-radius:50%; transform: scale(0); pointer-events:none;
    background: radial-gradient(circle, rgba(255,255,255,.45) 0%, rgba(255,255,255,.15) 40%, transparent 70%);
    animation: ripple .6s ease-out forwards;
  }
  @keyframes ripple{ to{ transform: scale(12); opacity:0; } }
</style>
@endpush

@section('content')
<div class="container py-4" id="admin-dashboard">

  {{-- Üst Şerit --}}
  <div class="hero mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
      <div>
        <div class="hero-title">Yönetim Paneli</div>
        <div class="hero-sub">Kullanıcılarınızı ve müşteri kayıtlarınızı tek ekrandan yönetin.</div>
      </div>

      <div class="header-buttons">
        <button type="button" class="theme-toggle" id="themeToggle" data-tip="Tema değiştir">
          <i class="fas fa-moon"></i><span class="d-none d-sm-inline">Tema</span>
        </button>
        <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn-glass btn-danger-grad" data-tip="Güvenli çıkış">
            <i class="fas fa-sign-out-alt"></i> <span class="d-none d-sm-inline">Çıkış Yap</span>
          </button>
        </form>
      </div>
    </div>

    <div class="divider"></div>

    {{-- KPI Mini Kartlar --}}
    @php
      $k = $kpis ?? [];
      $k_users = $k['users'] ?? 0;
      $k_users_active = $k['active_users'] ?? 0;
      $k_customers = $k['customers'] ?? 0;
      $k_customers_active = $k['active_customers'] ?? 0;
    @endphp

    <div class="kpi-grid">
      <div class="kpi" data-tip="Toplam sistem kullanıcısı">
        <div class="icon"><i class="fas fa-user-friends"></i></div>
        <div class="vals">
          <div class="label">Toplam Kullanıcı</div>
          <div class="val">{{ number_format($k_users) }}</div>
        </div>
      </div>

      <div class="kpi" data-tip="Son 30 gün aktif giriş yapanlar">
        <div class="icon"><i class="fas fa-user-check"></i></div>
        <div class="vals">
          <div class="label">Aktif Kullanıcı</div>
          <div class="val">{{ number_format($k_users_active) }}</div>
        </div>
      </div>

      <div class="kpi" data-tip="Toplam müşteri kaydı">
        <div class="icon"><i class="fas fa-address-book"></i></div>
        <div class="vals">
          <div class="label">Toplam Müşteri</div>
          <div class="val">{{ number_format($k_customers) }}</div>
        </div>
      </div>

      <div class="kpi" data-tip="Aktif ilişki/işlem gören müşteriler">
        <div class="icon"><i class="fas fa-user-tie"></i></div>
        <div class="vals">
          <div class="label">Aktif Müşteri</div>
          <div class="val">{{ number_format($k_customers_active) }}</div>
        </div>
      </div>
    </div>

  </div>

  {{-- Ana Kartlar --}}
  <div class="card-grid">

    {{-- Kullanıcı Yönetimi --}}
    <a href="{{ route('admin.users.index') }}" class="glass-card ripple-src">
      <div class="icon-wrap"><i class="fas fa-users-cog"></i></div>
      <div class="card-titles">
        <h3>Kullanıcı Yönetimi</h3>
        <p>Sistem kullanıcılarını görüntüle, yetkilendir, düzenle.</p>
      </div>
      <i class="fas fa-chevron-right chev"></i>
    </a>

    {{-- Müşteri Yönetimi --}}
    <a href="{{ route('admin.customers.index') }}" class="glass-card ripple-src">
      <div class="icon-wrap"><i class="fas fa-user-tie"></i></div>
      <div class="card-titles">
        <h3>Müşteri Yönetimi</h3>
        <p>Kayıtları incele, güncelle ve ilişkili süreçleri takip et.</p>
      </div>
      <i class="fas fa-chevron-right chev"></i>
    </a>

    {{-- İleride açılacaklar için şablonlar --}}
    {{-- <a href="{{ route('admin.reports.index') }}" class="glass-card ripple-src">
      <div class="icon-wrap"><i class="fas fa-chart-line"></i></div>
      <div class="card-titles">
        <h3>Raporlar</h3>
        <p>Satış & müşteri raporlarını hızlıca görüntüleyin.</p>
      </div>
      <i class="fas fa-chevron-right chev"></i>
    </a> --}}

    {{-- <a href="{{ route('admin.settings.index') }}" class="glass-card ripple-src">
      <div class="icon-wrap"><i class="fas fa-sliders-h"></i></div>
      <div class="card-titles">
        <h3>Ayarlar</h3>
        <p>Sistem tercihleri ve genel yapılandırmalar.</p>
      </div>
      <i class="fas fa-chevron-right chev"></i>
    </a> --}}
  </div>
</div>
@endsection

@push('scripts')
<script>
  // ---- Tema kalıcılığı ----
  (function initTheme(){
    const KEY = 'theme';
    const saved = localStorage.getItem(KEY);
    const root = document.documentElement;
    if (saved === 'light' || saved === 'dark') {
      root.setAttribute('data-theme', saved);
    } else {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      const val = prefersDark ? 'dark' : 'light';
      root.setAttribute('data-theme', val);
      localStorage.setItem(KEY, val);
    }
  })();

  // Tema toggle
  document.getElementById('themeToggle').addEventListener('click', function(){
    const root = document.documentElement;
    const KEY = 'theme';
    const curr = root.getAttribute('data-theme') || 'dark';
    const next = (curr === 'dark') ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    localStorage.setItem(KEY, next);
    // ikon değişimi
    this.innerHTML = (next === 'dark')
      ? '<i class="fas fa-moon"></i><span class="d-none d-sm-inline">Tema</span>'
      : '<i class="fas fa-sun"></i><span class="d-none d-sm-inline">Tema</span>';
  });

  // İlk ikon durumunu eşitle
  (function syncThemeIcon(){
    const btn = document.getElementById('themeToggle');
    const t = document.documentElement.getAttribute('data-theme') || 'dark';
    btn.innerHTML = (t === 'dark')
      ? '<i class="fas fa-moon"></i><span class="d-none d-sm-inline">Tema</span>'
      : '<i class="fas fa-sun"></i><span class="d-none d-sm-inline">Tema</span>';
  })();

  // ---- Ripple efekti ----
  document.querySelectorAll('.ripple-src').forEach(el => {
    el.addEventListener('click', function(e){
      const rect = this.getBoundingClientRect();
      const size = Math.max(rect.width, rect.height);
      const ripple = document.createElement('span');
      ripple.className = 'ripple';
      ripple.style.width = ripple.style.height = size + 'px';
      ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
      ripple.style.top  = (e.clientY - rect.top  - size/2) + 'px';
      this.appendChild(ripple);
      setTimeout(() => ripple.remove(), 650);
    });
  });
</script>
@endpush
