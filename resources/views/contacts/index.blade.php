@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Kişiler</h3>
        <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">Kişi Ekle</a>
      </div>
      <div class="card-body p-0">

        {{-- Masaüstü için tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Ad</th>
                <th>Pozisyon</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <th>Firma</th>
                <th>Güncellenme</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($contacts as $ct)
              <tr>
                <td>{{ $ct->name }}</td>
                <td>{{ $ct->position }}</td>
                <td>{{ $ct->email }}</td>
                <td>{{ $ct->phone }}</td>
                <td>{{ $ct->company?->company_name ?? 'Firma Yok' }}</td>
                <td>{{ \Carbon\Carbon::parse($ct->updated_at)->format('d.m.Y H:i') }}</td>
                <td>
                  <a href="{{ route('contacts.show',$ct) }}" class="btn btn-sm btn-info">Gör</a>
                  <a href="{{ route('contacts.edit',$ct) }}" class="btn btn-sm btn-warning">Düzenle</a>
                  <form action="{{ route('contacts.destroy',$ct) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">Kayıtlı kişi bulunamadı.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil için kart görünümü --}}
        <div class="mobile-cards">
          @forelse($contacts as $ct)
          <div class="card shadow-sm p-3 mb-3">
            <p><strong>Ad:</strong> {{ $ct->name }}</p>
            <p><strong>Pozisyon:</strong> {{ $ct->position }}</p>
            <p><strong>E-posta:</strong> {{ $ct->email }}</p>
            <p><strong>Telefon:</strong> {{ $ct->phone }}</p>
            <p><strong>Firma:</strong> {{ $ct->company?->company_name ?? 'Firma Yok' }}</p>
            <p><strong>Güncellenme:</strong> {{ \Carbon\Carbon::parse($ct->updated_at)->format('d.m.Y H:i') }}</p>
            <div class="mt-2">
              <a href="{{ route('contacts.show',$ct) }}" class="btn btn-sm btn-info">Gör</a>
              <a href="{{ route('contacts.edit',$ct) }}" class="btn btn-sm btn-warning">Düzenle</a>
              <form action="{{ route('contacts.destroy',$ct) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
              </form>
            </div>
          </div>
          @empty
          <p class="text-center p-3">Kayıtlı kişi bulunamadı.</p>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Masaüstü ve mobil görünüm */
  .mobile-cards { display: none; }
  @media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; }
  }

  /* Uzun metinler (e-posta gibi) için satır kırma */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }
</style>
@endpush
