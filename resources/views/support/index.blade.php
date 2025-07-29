@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Destek Talepleri</h3>
        <a href="{{ route('support.create') }}" class="btn btn-sm btn-primary">Yeni Talep</a>
      </div>

      <div class="card-body p-0">

        {{-- Masaüstü için tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Müşteri</th>
                <th>Başlık</th>
                <th>Durum</th>
                <th>Kayıt Tarihi</th>
                <th>Son Güncelleme</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($supports as $s)
                <tr>
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->title }}</td>
                  <td>
                    @switch($s->situation)
                      @case('pending')   <span class="badge badge-warning">Beklemede</span> @break
                      @case('resolved')  <span class="badge badge-success">Çözüldü</span>  @break
                      @default           <span class="badge badge-secondary">{{ ucfirst($s->situation) }}</span>
                    @endswitch
                  </td>
                  <td>{{ $s->registration_date }}</td>
                  <td>{{ $s->updated_at->format('Y-m-d') }}</td>
                  <td>
                    <a href="{{ route('support.show', $s) }}" class="btn btn-sm btn-info">Görüntüle</a>
                    <a href="{{ route('support.edit', $s) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('support.destroy', $s) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center">Talep bulunamadı.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil için kart görünümü --}}
        <div class="mobile-cards p-3">
          @forelse($supports as $s)
            <div class="card shadow-sm p-3 mb-3">
              <p><strong>Müşteri:</strong> {{ $s->customer->customer_name }}</p>
              <p><strong>Başlık:</strong> {{ $s->title }}</p>
              <p><strong>Durum:</strong>
                @switch($s->situation)
                  @case('pending')   <span class="badge badge-warning">Beklemede</span> @break
                  @case('resolved')  <span class="badge badge-success">Çözüldü</span>  @break
                  @default           <span class="badge badge-secondary">{{ ucfirst($s->situation) }}</span>
                @endswitch
              </p>
              <p><strong>Kayıt Tarihi:</strong> {{ $s->registration_date }}</p>
              <p><strong>Son Güncelleme:</strong> {{ $s->updated_at->format('Y-m-d') }}</p>
              <div class="mt-2">
                <a href="{{ route('support.show', $s) }}" class="btn btn-sm btn-info">Görüntüle</a>
                <a href="{{ route('support.edit', $s) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('support.destroy', $s) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-center text-muted">Talep bulunamadı.</p>
          @endforelse
        </div>

      </div>
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

  /* Satır kırma ve düzen */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }
</style>
@endpush
