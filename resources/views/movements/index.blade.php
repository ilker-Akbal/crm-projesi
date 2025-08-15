@extends('layouts.app')

@section('content')
<section id="movements-page" class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">

            {{-- Başlık + Yeni Kayıt --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Hesap Hareketleri</h3>
                <a href="{{ route('movements.create') }}" class="btn btn-sm btn-primary">Hareket Ekle</a>
            </div>

            {{-- Hesap etiketi --}}
            <div class="px-3 py-2 bg-light border-bottom">
                <strong>Hesap:</strong>
                {{ auth()->user()->name ?? auth()->user()->customer_name ?? (auth()->user()->customer->customer_name ?? auth()->user()->email) }}
            </div>

            {{-- ✨ Filtre Formu + PDF --}}
            <form method="GET" action="{{ route('movements.index') }}" class="p-3 border-bottom filter-form">
                <div class="row g-2">
                    <div class="col-sm"><input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm" placeholder="Başlangıç"></div>
                    <div class="col-sm"><input type="date" name="to"   value="{{ request('to') }}"   class="form-control form-control-sm" placeholder="Bitiş"></div>
                    <div class="col-sm">
                        <select name="type" class="form-select form-select-sm">
                            <option value="">Tümü</option>
                            <option value="Debit"  @selected(request('type')==='Debit')>Alış</option>
                            <option value="Credit" @selected(request('type')==='Credit')>Satış</option>
                        </select>
                    </div>
                    <div class="col-sm"><input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Açıklamada ara"></div>
                    <div class="col-auto d-grid"><button class="btn btn-sm btn-primary">Filtrele</button></div>
                    <div class="col-auto ms-auto"><a href="{{ route('movements.pdf.filter', request()->all()) }}" class="btn btn-sm btn-danger">Filtreli PDF</a></div>
                    <div class="col-auto"><a href="{{ route('movements.pdf') }}" class="btn btn-sm btn-outline-secondary">Tümünü PDF</a></div>
                </div>
            </form>

            {{-- =================== Veri Hazırlama =================== --}}
            @php
                $rows = ($movements instanceof \Illuminate\Pagination\AbstractPaginator)
                        ? $movements->getCollection()->sortBy([['departure_date','asc'], ['id','asc']])
                        : $movements->sortBy([['departure_date','asc'], ['id','asc']]);
            @endphp

            {{-- =================== Masaüstü TABLO =================== --}}
            <div class="card-body p-0 desktop-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tarih</th>
                                <th class="text-end">Alış</th>
                                <th class="text-end">Satış</th>
                                <th class="text-end">Bakiye</th>
                                <th>Açıklama</th>
                                <th class="text-center" style="width:110px;white-space:nowrap">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $m)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</td>
                                    <td class="text-end text-danger">{{ $m->movement_type==='Debit' ? number_format($m->amount,2,',','.') : '' }}</td>
                                    <td class="text-end text-success">{{ $m->movement_type==='Credit'? number_format($m->amount,2,',','.') : '' }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($m->running_balance,2,',','.') }}</td>
                                    <td>{{ $m->explanation }}</td>
                                    <td class="text-center"><a href="{{ route('movements.show', $m) }}" class="btn btn-xs btn-info">Görüntüle</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4">Kayıt bulunamadı.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- =================== MOBİL KARTLAR =================== --}}
            <div class="mobile-cards p-3">
                @forelse ($rows as $m)
                    <div class="card shadow-sm p-3 mb-3">
                        <p><strong>Tarih:</strong> {{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</p>
                        <p><strong>Alış:</strong>  {!! $m->movement_type==='Debit'  ? '<span class="text-danger">'.number_format($m->amount,2,',','.').'</span>' : '—' !!}</p>
                        <p><strong>Satış:</strong> {!! $m->movement_type==='Credit' ? '<span class="text-success">'.number_format($m->amount,2,',','.').'</span>' : '—' !!}</p>
                        <p><strong>Bakiye:</strong> {{ number_format($m->running_balance,2,',','.') }}</p>
                        <p><strong>Açıklama:</strong> {{ $m->explanation }}</p>
                        <div class="mt-2 text-end"><a href="{{ route('movements.show', $m) }}" class="btn btn-sm btn-info">Görüntüle</a></div>
                    </div>
                @empty
                    <p class="text-center text-muted">Kayıt bulunamadı.</p>
                @endforelse
            </div>

            {{-- Sayfalama kaldırıldı --}}
            {{-- 
            @if (method_exists($movements,'links'))
                <div class="card-footer">
                    {{ $movements->links() }}
                </div>
            @endif
            --}}

        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Bu sayfaya özel: içerik alanını temizle */
#movements-page,
#movements-page .container-fluid,
#movements-page .card,
#movements-page .card-body {
  background: #f4f6f9 !important;
  background-image: none !important;
}

#movements-page *::before,
#movements-page *::after {
  content: none !important;
  background: none !important;
}

#movements-page .blob,
#movements-page .shape,
#movements-page .decor,
#movements-page .bg-decor,
#movements-page [class*="blob"],
#movements-page [class*="shape"],
#movements-page [class*="decor"] {
  display: none !important;
}

.content-wrapper { overflow: hidden !important; }

/* Mevcut sayfa minör stiller */
.mobile-cards{display:none}
@media (max-width:768px){.desktop-table{display:none}.mobile-cards{display:block}}
.table td,.table th{white-space:normal!important;word-break:break-word}
.table .btn{white-space:nowrap}
.filter-form .form-control,.filter-form .form-select{margin-bottom:10px}
.filter-form .row.g-2>[class*='col-']{margin-bottom:8px}
</style>
@endpush
