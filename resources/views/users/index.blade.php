{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title','Kullanıcılar')

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
    --warning: #f59e0b;
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
  .hero:before{
    content:""; position:absolute; inset:-1px; border-radius: 20px; padding:1px;
    background: linear-gradient(135deg, rgba(124,58,237,.35), rgba(6,182,212,.35), transparent 70%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude; pointer-events:none;
  }
  .hero-title{ font-weight: 800; font-size: clamp(1.2rem, 2.2vw, 1.6rem); letter-spacing:.2px }
  .hero-sub{ color: var(--muted) }

  .toolbar{ display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between; margin-top:10px; }
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
    text-decoration:none !important;
    transition: transform .12s ease, box-shadow .12s ease;
  }
  .btn-glass:hover{ transform: translateY(-1px); }
  .btn-primary-grad{ background: linear-gradient(135deg, var(--primary), var(--primary-2)); color:#fff; border:none; }
  .btn-danger-grad{ background: linear-gradient(135deg, var(--danger), #b5179e); color:#fff; border:none; }

  .search-input{
    border:1px solid var(--stroke);
    background: var(--panel);
    color: var(--text);
    height: 42px;
    border-radius: 12px;
    padding: 0 .9rem;
    outline:none;
    min-width:260px;
    box-shadow: var(--shadow);
  }
  .search-input::placeholder{ color: var(--muted); }

  /* Cam tablo */
  .glass-table{
    width:100%;
    border-collapse: separate;
    border-spacing: 0 10px;
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

  /* Küçük rozetler */
  .badge-success{
    background: linear-gradient(135deg, #22c55e, #15803d);
    color:#fff; padding:4px 10px; border-radius:6px; font-size:.8rem;
  }
  .badge-danger{
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color:#fff; padding:4px 10px; border-radius:6px; font-size:.8rem;
  }

  /* Ripple */
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
        <div class="hero-title">Kullanıcılar</div>
        <div class="hero-sub">Sistemdeki kullanıcı kayıtlarını görüntüleyin ve yönetin.</div>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ url('/admin') }}" class="btn-glass">
          <i class="fas fa-arrow-left"></i> Geri
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn-glass btn-primary-grad ripple-src">
          <i class="fas fa-user-plus"></i> Yeni Kullanıcı Ekle
        </a>
      </div>
    </div>

    {{-- Arama/Form araçları (opsiyonel) --}}
    <div class="toolbar">
      <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2 align-items-center">
        <input type="text" name="q" value="{{ request('q') }}" class="search-input" placeholder="İsim...">
        <button type="submit" class="btn-glass">
          <i class="fas fa-search"></i> Ara
        </button>
        @if(request()->has('q') && request('q')!=='')
          <a href="{{ route('admin.users.index') }}" class="btn-glass btn-danger-grad">
            <i class="fas fa-times"></i> Temizle
          </a>
        @endif
      </form>
      <div>
        @isset($users)
          <span class="btn-glass" style="pointer-events:none;">
            <i class="fas fa-database me-1"></i>
            {{ method_exists($users,'total') ? $users->total() : $users->count() }} kayıt
          </span>
        @endisset
      </div>
    </div>
  </div>

  {{-- Tablo --}}
  <div class="table-wrap">
    <table class="glass-table">
      <thead>
        <tr>
          <th>Kullanıcı Adı</th>
          <th>Rol</th>
          <th>Aktif</th>
          <th style="width:260px">İşlemler</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          @php
            $displayName = $user->username
                          ?? ($user->name ?? trim(($user->first_name ?? '').' '.($user->last_name ?? '')));
          @endphp
          <tr class="glass-row">
            <td>{{ $displayName ?? '—' }}</td>
            <td>{{ ucfirst($user->role ?? 'user') }}</td>
            <td>
              @if(!empty($user->active))
                <span class="badge-success">Evet</span>
              @else
                <span class="badge-danger">Hayır</span>
              @endif
            </td>
            <td>
              <div class="actions">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-glass ripple-src">
                  <i class="fas fa-edit"></i> Düzenle
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-glass btn-danger-grad ripple-src"
                          onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">
                    <i class="fas fa-trash-alt"></i> Sil
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr class="glass-row">
            <td colspan="4" class="text-center" style="padding:24px">Kayıtlı kullanıcı bulunamadı.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Sayfalama --}}
  @if(method_exists($users,'links'))
    <div class="mt-3">
      {{ $users->withQueryString()->links() }}
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
