@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Firmalar</h3>
        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">
          Firma Ekle
        </a>
      </div>

      {{-- ==== PDF & Tarih Filtresi ==== --}}
      <div class="card-body">
        <form action="{{ route('companies.pdf.filter') }}" method="GET"
              class="row g-2 align-items-end mb-3">

          {{-- Başlangıç tarihi --}}
          <div class="col-12 col-md-3">
            <label class="form-label mb-1 fw-semibold">Başlangıç</label>
            <input type="date" name="start" class="form-control" required>
          </div>

          {{-- Bitiş tarihi --}}
          <div class="col-12 col-md-3">
            <label class="form-label mb-1 fw-semibold">Bitiş</label>
            <input type="date" name="end" class="form-control" required>
          </div>

          {{-- Filtreli PDF --}}
          <div class="col-12 col-md-auto">
            <button class="btn btn-danger w-100">
              Filtreli PDF İndir
            </button>
          </div>

          {{-- Tümünü PDF --}}
          <div class="col-12 col-md-auto ms-md-auto">
            <a href="{{ route('companies.pdf') }}" class="btn btn-outline-secondary w-100">
              Tümünü PDF İndir
            </a>
          </div>
        </form>
      </div>

      <div class="card-body p-0">

        {{-- Masaüstü tablo --}}
        <div class="desktop-table table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Firma Adı</th>
                <th>Vergi Numarası</th>
                <th>Telefon</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>Kayıt Tarihi</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($companies as $c)
              <tr>
                <td>{{ $c->company_name }}</td>
                <td>{{ $c->tax_number }}</td>
                <td>{{ $c->phone_number }}</td>
                <td>{{ $c->email }}</td>
                <td>
                  @switch($c->current_role)
                    @case('customer') Müşteri @break
                    @case('supplier') Tedarikçi @break
                    @case('candidate') Aday @break
                    @default Belirtilmemiş
                  @endswitch
                </td>
                <td>{{ \Carbon\Carbon::parse($c->registration_date)->format('d.m.Y') }}</td>
                <td>
                  <a href="{{ route('companies.show',$c) }}" class="btn btn-sm btn-info">Gör</a>
                  <a href="{{ route('companies.edit',$c) }}" class="btn btn-sm btn-warning">Düzenle</a>
                  <form action="{{ route('companies.destroy',$c) }}" method="POST"
                        class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Silinsin mi?')"
                            class="btn btn-sm btn-danger">
                      Sil
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">Kayıtlı firma bulunamadı.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Mobil kart görünümü --}}
        <div class="mobile-cards">
          @forelse($companies as $c)
            <div class="card shadow-sm p-3 mb-3">
              <p><strong>Firma Adı:</strong> {{ $c->company_name }}</p>
              <p><strong>Vergi No:</strong> {{ $c->tax_number }}</p>
              <p><strong>Telefon:</strong> {{ $c->phone_number }}</p>
              <p><strong>E-posta:</strong> {{ $c->email }}</p>
              <p><strong>Rol:</strong>
                @switch($c->current_role)
                  @case('customer') Müşteri @break
                  @case('supplier') Tedarikçi @break
                  @case('candidate') Aday @break
                  @default Belirtilmemiş
                @endswitch
              </p>
              <p><strong>Kayıt Tarihi:</strong>
                 {{ \Carbon\Carbon::parse($c->registration_date)->format('d.m.Y') }}</p>
              <div class="mt-2 d-flex gap-2">
                <a href="{{ route('companies.show',$c) }}" class="btn btn-sm btn-info flex-fill">
                  Gör
                </a>
                <a href="{{ route('companies.edit',$c) }}" class="btn btn-sm btn-warning flex-fill">
                  Düzenle
                </a>
                <form action="{{ route('companies.destroy',$c) }}" method="POST" class="flex-fill">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Silinsin mi?')"
                          class="btn btn-sm btn-danger w-100">Sil</button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-center p-3">Kayıtlı firma bulunamadı.</p>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Masaüstü / mobil ayrımı */
  .mobile-cards { display: none; }
  @media (max-width: 768px) {
    .desktop-table { display: none; }
    .mobile-cards { display: block; }
  }
  /* Uzun metinler kırılmasın */
  .table td, .table th {
    white-space: normal !important;
    word-break: break-word;
  }
</style>
@endpush
