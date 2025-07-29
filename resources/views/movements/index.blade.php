@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- Başlık + Yeni Kayıt --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Hesap Hareketleri</h3>
        <a href="{{ route('movements.create') }}" class="btn btn-sm btn-primary">Hareket Ekle</a>
      </div>

      {{-- Filtre Formu --}}
      <form method="GET" class="p-3 border-bottom filter-form">
        <div class="row g-2">
          <div class="col-sm">
            <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="Başlangıç Tarihi">
          </div>
          <div class="col-sm">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="Bitiş Tarihi">
          </div>
          <div class="col-sm">
            <select name="type" class="form-select">
              <option value="">Tüm Türler</option>
              <option value="Debit"  @selected(request('type')=='Debit') >Borç</option>
              <option value="Credit" @selected(request('type')=='Credit')>Alacak</option>
            </select>
          </div>
          <div class="col-sm">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Açıklamada Ara">
          </div>
          <div class="col-auto">
            <button class="btn btn-primary">Filtrele</button>
          </div>
        </div>
      </form>

      {{-- Masaüstü için tablo --}}
      <div class="card-body p-0 desktop-table">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th>Hesap</th>
                <th>Tarih</th>
                <th class="text-end">Borç</th>
                <th class="text-end">Alacak</th>
                <th class="text-end">Bakiye</th>
                <th>Açıklama</th>
                <th style="width:140px">İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @php $running = 0; @endphp
              @forelse($movements as $m)
                @php
                  $delta   = $m->movement_type === 'Debit' ? -$m->amount : $m->amount;
                  $running+= $delta;
                @endphp
                <tr>
                  <td>{{ $m->currentCard->customer->customer_name }} ({{ $m->current_id }})</td>
                  <td>{{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</td>
                  <td class="text-end text-danger">{{ $m->movement_type=='Debit' ? number_format($m->amount,2) : '' }}</td>
                  <td class="text-end text-success">{{ $m->movement_type=='Credit' ? number_format($m->amount,2) : '' }}</td>
                  <td class="text-end fw-semibold">{{ number_format($running,2) }}</td>
                  <td>{{ $m->explanation }}</td>
                  <td>
                    <a href="{{ route('movements.show',$m) }}" class="btn btn-xs btn-info">Görüntüle</a>
                    <a href="{{ route('movements.edit',$m) }}" class="btn btn-xs btn-warning">Düzenle</a>
                    <form action="{{ route('movements.destroy',$m) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button onclick="return confirm('Silinsin mi?')" class="btn btn-xs btn-danger">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">Hiç hareket bulunamadı.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Mobil için kart görünümü --}}
      <div class="mobile-cards p-3">
        @php $running = 0; @endphp
        @forelse($movements as $m)
          @php
            $delta   = $m->movement_type === 'Debit' ? -$m->amount : $m->amount;
            $running+= $delta;
          @endphp
          <div class="card shadow-sm p-3 mb-3">
            <p><strong>Hesap:</strong> {{ $m->currentCard->customer->customer_name }} ({{ $m->current_id }})</p>
            <p><strong>Tarih:</strong> {{ \Carbon\Carbon::parse($m->departure_date)->format('d.m.Y') }}</p>
            <p><strong>Borç:</strong>
              @if($m->movement_type=='Debit')
                <span class="text-danger">{{ number_format($m->amount,2) }}</span>
              @else
                —
              @endif
            </p>
            <p><strong>Alacak:</strong>
              @if($m->movement_type=='Credit')
                <span class="text-success">{{ number_format($m->amount,2) }}</span>
              @else
                —
              @endif
            </p>
            <p><strong>Bakiye:</strong> {{ number_format($running,2) }}</p>
            <p><strong>Açıklama:</strong> {{ $m->explanation }}</p>
            <div class="mt-2">
              <a href="{{ route('movements.show',$m) }}" class="btn btn-sm btn-info">Görüntüle</a>
              <a href="{{ route('movements.edit',$m) }}" class="btn btn-sm btn-warning">Düzenle</a>
              <form action="{{ route('movements.destroy',$m) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
              </form>
            </div>
          </div>
        @empty
          <p class="text-center text-muted">Hiç hareket bulunamadı.</p>
        @endforelse
      </div>

      {{-- Sayfalama --}}
      @if(method_exists($movements,'links'))
        <div class="card-footer">{{ $movements->links() }}</div>
      @endif
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Masaüstü ve mobil görünüm ayırımı */
  .mobile-cards { display: none; }
  @media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; }
  }

  /* Satır kırma ve düzgün görünüm */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }

  /* Filtre formu aralıkları */
  .filter-form .form-control,
  .filter-form .form-select {
    margin-bottom: 10px;
  }
  .filter-form .row.g-2 > [class*='col-'] {
    margin-bottom: 8px;
  }
  .filter-form button {
    margin-top: 5px;
  }
</style>
@endpush
