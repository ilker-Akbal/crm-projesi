@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Teklifler</h3>
        <a href="{{ route('offers.create') }}" class="btn btn-sm btn-primary">Teklif Ekle</a>
      </div>
      <div class="card-body p-0">

        {{-- Masaüstü için tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Şirket</th>
                <th>Teklif Tarihi</th>
                <th>Geçerlilik Tarihi</th>
                <th>Durum</th>
                <th class="text-end">Toplam</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($offers as $offer)
              @php
                // Geçerlilik tarihi geçmişse kontrolü
                $isExpired = $offer->valid_until && \Carbon\Carbon::parse($offer->valid_until)->isPast();
              @endphp
              <tr class="{{ $isExpired ? 'expired-offer' : '' }}">
                <td>{{ $offer->company?->company_name ?? '—' }}</td>
                <td>{{ \Carbon\Carbon::parse($offer->offer_date)->format('d.m.Y') }}</td>
                <td>
                  @if($isExpired)
                    <span class="text-danger fw-bold me-1">
                      <i class="fas fa-times-circle"></i>
                    </span>
                  @endif
                  {{ $offer->valid_until ? \Carbon\Carbon::parse($offer->valid_until)->format('d.m.Y') : '—' }}
                </td>
                <td>{{ ucfirst($offer->status) }}</td>
                <td class="text-end">{{ number_format($offer->total_amount, 2) }} ₺</td>
                <td>
                  <a href="{{ route('offers.show',$offer) }}" class="btn btn-sm btn-info">Göster</a>
                  <a href="{{ route('offers.edit',$offer) }}" class="btn btn-sm btn-warning">Düzenle</a>
                  <form action="{{ route('offers.destroy',$offer) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">Teklif bulunamadı.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil için kart görünümü --}}
        <div class="mobile-cards p-3">
          @forelse($offers as $offer)
          @php
            $isExpired = $offer->valid_until && \Carbon\Carbon::parse($offer->valid_until)->isPast();
          @endphp
          <div class="card shadow-sm p-3 mb-3 {{ $isExpired ? 'expired-offer' : '' }}">
            <p><strong>Şirket:</strong> {{ $offer->company?->company_name ?? '—' }}</p>
            <p><strong>Teklif Tarihi:</strong> {{ \Carbon\Carbon::parse($offer->offer_date)->format('d.m.Y') }}</p>
            <p><strong>Geçerlilik Tarihi:</strong>
              @if($isExpired)
                <span class="text-danger fw-bold me-1">
                  <i class="fas fa-times-circle"></i>
                </span>
              @endif
              {{ $offer->valid_until ? \Carbon\Carbon::parse($offer->valid_until)->format('d.m.Y') : '—' }}
            </p>
            <p><strong>Durum:</strong> {{ ucfirst($offer->status) }}</p>
            <p><strong>Toplam:</strong> {{ number_format($offer->total_amount, 2) }} ₺</p>
            <div class="mt-2">
              <a href="{{ route('offers.show',$offer) }}" class="btn btn-sm btn-info">Göster</a>
              <a href="{{ route('offers.edit',$offer) }}" class="btn btn-sm btn-warning">Düzenle</a>
              <form action="{{ route('offers.destroy',$offer) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
              </form>
            </div>
          </div>
          @empty
          <p class="text-center text-muted">Teklif bulunamadı.</p>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Masaüstü ve mobil görünümü ayır */
  .mobile-cards { display: none; }
  @media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; }
  }

  /* Uzun metinlerin satır kırması */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }

  /* Süresi dolmuş teklifler için kırmızı renk ve ikon */
  .expired-offer {
    color: #dc3545; /* Bootstrap'in danger rengi */
    font-weight: bold;
  }
  .expired-offer .text-muted {
      color: #dc3545 !important;
  }
</style>
@endpush