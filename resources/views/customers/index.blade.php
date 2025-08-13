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

  /* 🔍 Arama alanı */
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

        {{-- 🔍 Canlı Arama --}}
        <div class="search-wrap d-flex align-items-center" style="gap:8px;">
          <input id="customerSearch" type="text" placeholder="Müşteri ara..." class="search-input" autocomplete="off" />
          <button id="clearCustomerSearch" class="btn-glass" type="button" title="Temizle">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>

    {{-- İstatistik Kutuları --}}
    <div class="stats-grid">
      <div class="stat-box">
        <div class="stat-title"><i class="fas fa-database"></i> Toplam Müşteri</div>
        <div class="stat-value" id="totalCount">{{ $customers->count() }}</div>
        <small id="filteredNote" style="display:none;color:var(--muted)"></small>
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
          @php
            $types = [
              'supplier' => 'Tedarikçi',
              'candidate' => 'Aday',
              'customer' => 'Müşteri'
            ];
          @endphp
          <tr class="glass-row">
            <td>{{ $customer->customer_name }}</td>
            <td>{{ $types[$customer->customer_type] ?? ucfirst($customer->customer_type) }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->updated_at ? $customer->updated_at->locale('tr')->diffForHumans() : '-' }}</td>
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
      @php
        $types = [
          'supplier' => 'Tedarikçi',
          'candidate' => 'Aday',
          'manager' => 'Yönetici'
        ];
      @endphp
      <div class="card-item glass-row" style="padding:14px;">
        <strong>{{ $customer->customer_name }}</strong> - {{ $types[$customer->customer_type] ?? ucfirst($customer->customer_type) }}
        <div style="color: var(--muted)">{{ $customer->email }}</div>
        <small>Güncellendi: {{ $customer->updated_at ? $customer->updated_at->locale('tr')->diffForHumans() : '-' }}</small>
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

  {{-- Laravel Sayfalama (JS bunu gizleyecek) --}}
  @if(method_exists($customers,'links'))
    <div class="mt-3" id="paginationWrap">
      {{ $customers->links() }}
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
(function(){
  // === AYAR ===
  const PAGE_SIZE = 10; // her sayfada 10 kayıt

  // TR uyumlu lower
  const trLower = (s) => (s || '').toLocaleLowerCase('tr');

  // DOM
  const input       = document.getElementById('customerSearch');
  const clearBtn    = document.getElementById('clearCustomerSearch');

  const tableBody   = document.querySelector('.table-wrap tbody');
  const tableRows   = tableBody ? Array.from(tableBody.querySelectorAll('tr.glass-row')) : [];

  const cardsWrap   = document.querySelector('.cards');
  const cardItems   = cardsWrap ? Array.from(cardsWrap.querySelectorAll('.card-item.glass-row, .cards .card-item')) : [];

  const laravelPagination = document.getElementById('paginationWrap');

  // Client pager
  const pagerWrap   = document.getElementById('clientPager');
  const prevBtn     = document.getElementById('prevPage');
  const nextBtn     = document.getElementById('nextPage');
  const pageNumbers = document.getElementById('pageNumbers');

  // "Sonuç yok"
  let noRowEl = null, noCardEl = null;
  function ensureNoEls(){
    if(tableBody && !noRowEl){
      noRowEl = document.createElement('tr');
      noRowEl.className = 'glass-row';
      noRowEl.innerHTML = `<td colspan="5" class="no-results">Sonuç bulunamadı.</td>`;
    }
    if(cardsWrap && !noCardEl){
      noCardEl = document.createElement('div');
      noCardEl.className = 'card-item no-results';
      noCardEl.textContent = 'Sonuç bulunamadı.';
      noCardEl.style.padding = '24px';
    }
  }

  // İki görünüm aynı sırada render edildiği için indeksler paralel kabul
  const lengthBy   = tableRows.length || cardItems.length;
  const indicesAll = Array.from({length: lengthBy}, (_,i)=>i);

  // Dinamik durum
  let filteredIdx = indicesAll.slice(); // filtre sonrası görünür indeksler
  let currentPage = 1;

  function hideAll(){
    tableRows.forEach(tr => tr.style.display = 'none');
    cardItems.forEach(c => c.style.display = 'none');
    if(noRowEl && tableBody?.contains(noRowEl)) tableBody.removeChild(noRowEl);
    if(noCardEl && cardsWrap?.contains(noCardEl)) cardsWrap.removeChild(noCardEl);
  }

  function renderPage(){
    // Server-side pagination'ı gizle
    if(laravelPagination) laravelPagination.style.display = 'none';

    hideAll();

    if(filteredIdx.length === 0){
      ensureNoEls();
      if(tableRows.length && tableBody && !tableBody.contains(noRowEl)) tableBody.appendChild(noRowEl);
      if(cardItems.length && cardsWrap && !cardsWrap.contains(noCardEl)) cardsWrap.appendChild(noCardEl);
      buildPager(1);
      return;
    }

    const totalPages = Math.max(1, Math.ceil(filteredIdx.length / PAGE_SIZE));
    if(currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * PAGE_SIZE;
    const end   = Math.min(start + PAGE_SIZE, filteredIdx.length);

    for(let i = start; i < end; i++){
      const idx = filteredIdx[i];
      if(tableRows[idx]) tableRows[idx].style.display = '';
      if(cardItems[idx]) cardItems[idx].style.display = '';
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

    // Sayı düğmeleri (7 sınırı + …)
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

    // Öncelik masaüstü tablo; yoksa kartlar
    const ref = tableRows.length ? tableRows : cardItems;
    ref.forEach((el, i) => {
      const text = trLower(el.textContent);
      const match = q === '' ? true : text.includes(q);
      if(match) filteredIdx.push(i);
    });

    currentPage = 1; // aramada başa dön
    renderPage();
  }

  // Etkinlikler
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
