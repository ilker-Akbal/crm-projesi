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
  .badge-success{
    background: linear-gradient(135deg, #22c55e, #15803d);
    color:#fff; padding:4px 10px; border-radius:6px; font-size:.8rem;
  }
  .badge-danger{
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    color:#fff; padding:4px 10px; border-radius:6px; font-size:.8rem;
  }
  .ripple{ position:absolute; border-radius:50%; transform: scale(0); pointer-events:none;
    background: radial-gradient(circle, rgba(255,255,255,.6) 0%, rgba(255,255,255,.25) 40%, transparent 70%);
    animation: ripple .6s ease-out forwards;
  }
  @keyframes ripple{ to{ transform: scale(12); opacity:0; } }

  /* 🔍 Arama kutusu stilleri */
  .search-input{
    height: 42px;
    border-radius: 12px;
    border:1px solid var(--stroke);
    background: var(--panel);
    box-shadow: var(--shadow);
    padding: 0 .9rem;
    min-width: 260px;
    font-weight: 600;
    color: var(--text);
    outline: none;
  }
  .search-input::placeholder{ color: var(--muted); font-weight: 500; }
  .no-results{
    text-align:center; padding:24px; color: var(--muted);
  }

  /* 🔽 Client pager */
  #clientPager .btn-glass[disabled]{ opacity:.55; cursor: not-allowed; }
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

    {{-- Kayıt sayısı + Canlı arama --}}
    <div class="toolbar">
      <div>
        @isset($users)
          <span class="btn-glass" style="pointer-events:none;">
            <i class="fas fa-database me-1"></i>
            {{ method_exists($users,'total') ? $users->total() : $users->count() }} kayıt
          </span>
        @endisset
      </div>

      {{-- 🔍 Canlı Arama --}}
      <div class="search-wrap" style="display:flex;gap:8px;align-items:center;">
        <input id="userSearch" type="text" placeholder="Kullanıcı ara..." class="search-input" autocomplete="off" />
        <button id="clearSearch" class="btn-glass" type="button" title="Temizle">
          <i class="fas fa-times"></i>
        </button>
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
            $roleMap = [
              'supplier' => 'Tedarikçi',
              'candidate' => 'Aday',
              'manager' => 'Yönetici',
              'admin' => 'Yönetici',
              'user' => 'Kullanıcı'
            ];
            $roleText = $roleMap[$user->role] ?? ucfirst($user->role ?? 'Kullanıcı');
          @endphp
          <tr class="glass-row">
            <td>{{ $displayName ?? '—' }}</td>
            <td>{{ $roleText }}</td>
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

  {{-- Laravel Sayfalama (JS bunu gizleyecek) --}}
  @if(method_exists($users,'links'))
    <div class="mt-3" id="paginationWrap">
      {{ $users->withQueryString()->links() }}
    </div>
  @endif

  {{-- 🔽 Frontend (JS) sayfalama --}}
  <div id="clientPager" class="d-flex align-items-center justify-content-center" style="gap:8px; margin-top:12px; flex-wrap:wrap;">
    <button id="prevPage" class="btn-glass" type="button" aria-label="Önceki">&laquo;</button>
    <div id="pageNumbers" class="d-flex" style="gap:6px; flex-wrap:wrap;"></div>
    <button id="nextPage" class="btn-glass" type="button" aria-label="Sonraki">&raquo;</button>
  </div>

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

  // 🔍 Canlı arama + numaralı client-side sayfalama (10/sayfa)
  (function(){
    const PAGE_SIZE = 10; // her sayfada 10 kayıt
    const trLower = (s) => (s || '').toLocaleLowerCase('tr');

    const input     = document.getElementById('userSearch');
    const clearBtn  = document.getElementById('clearSearch');

    const tbody     = document.querySelector('table.glass-table tbody');
    const rows      = tbody ? Array.from(tbody.querySelectorAll('tr.glass-row')) : [];

    const laravelPagination = document.getElementById('paginationWrap');

    // Client pager
    const pagerWrap   = document.getElementById('clientPager');
    const prevBtn     = document.getElementById('prevPage');
    const nextBtn     = document.getElementById('nextPage');
    const pageNumbers = document.getElementById('pageNumbers');

    let noRowEl = null;
    function ensureNoResultsEl(){
      if(!noRowEl){
        noRowEl = document.createElement('tr');
        noRowEl.className = 'glass-row';
        noRowEl.innerHTML = `<td colspan="4" class="no-results">Sonuç bulunamadı.</td>`;
      }
    }

    const indicesAll = rows.map((_, i) => i);
    let filteredIdx  = indicesAll.slice();
    let currentPage  = 1;

    function hideAll(){
      rows.forEach(tr => tr.style.display = 'none');
      if(noRowEl && tbody?.contains(noRowEl)) tbody.removeChild(noRowEl);
    }

    function renderPage(){
      // Server-side pagination'ı gizle
      if(laravelPagination) laravelPagination.style.display = 'none';

      hideAll();

      if(filteredIdx.length === 0){
        ensureNoResultsEl();
        if(!tbody.contains(noRowEl)) tbody.appendChild(noRowEl);
        buildPager(1);
        return;
      }

      const totalPages = Math.max(1, Math.ceil(filteredIdx.length / PAGE_SIZE));
      if(currentPage > totalPages) currentPage = totalPages;

      const start = (currentPage - 1) * PAGE_SIZE;
      const end   = Math.min(start + PAGE_SIZE, filteredIdx.length);

      for(let i = start; i < end; i++){
        const idx = filteredIdx[i];
        rows[idx].style.display = '';
      }

      buildPager(totalPages);
    }

    function buildPager(totalPages){
      if(!pagerWrap) return;

      // Prev/Next
      prevBtn.disabled = (currentPage === 1);
      nextBtn.disabled = (currentPage === totalPages);

      prevBtn.onclick = () => { if(currentPage > 1){ currentPage--; renderPage(); } };
      nextBtn.onclick = () => { if(currentPage < totalPages){ currentPage++; renderPage(); } };

      // 1-2-3 … (maks 7 buton)
      pageNumbers.innerHTML = '';
      const MAX = 7;
      let start = Math.max(1, currentPage - 3);
      let end   = Math.min(totalPages, start + MAX - 1);
      start     = Math.max(1, end - MAX + 1);

      const addBtn = (label, page, opts={active:false, disabled:false}) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = 'btn-glass';
        b.textContent = label;
        if(opts.active){ b.style.fontWeight = '800'; }
        if(opts.disabled){ b.disabled = true; }
        if(!opts.disabled){
          b.addEventListener('click', () => { currentPage = page; renderPage(); });
        }
        pageNumbers.appendChild(b);
      };

      if(start > 1){ addBtn('1', 1); if(start > 2) addBtn('…', currentPage, {disabled:true}); }
      for(let p = start; p <= end; p++){
        addBtn(String(p), p, {active: p === currentPage});
      }
      if(end < totalPages){ if(end < totalPages - 1) addBtn('…', currentPage, {disabled:true}); addBtn(String(totalPages), totalPages); }
    }

    function runFilter(){
      const q = trLower(input?.value?.trim() || '');
      filteredIdx = [];
      rows.forEach((tr, i) => {
        const text = trLower(tr.textContent);
        const match = q === '' ? true : text.includes(q);
        if(match) filteredIdx.push(i);
      });
      currentPage = 1; // aramada başa dön
      renderPage();
    }

    // Eventler
    input?.addEventListener('input', runFilter);
    clearBtn?.addEventListener('click', () => {
      input.value = '';
      runFilter();
      input.focus();
    });

    // İlk yükleme
    runFilter();
  })();
</script>
@endpush
