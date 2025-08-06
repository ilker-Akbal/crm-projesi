@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      {{-- ==================== BAŞLIK SATIRI ==================== --}}
      <div class="card-header d-flex align-items-center position-relative">
        <h3 class="card-title mb-0 me-3">Kişiler</h3>

        {{-- Orta: Kişi Ekle --}}
        <a href="{{ route('contacts.create') }}"
           class="btn btn-sm btn-primary header-center">
          Kişi&nbsp;Ekle
        </a>

        <div class="flex-grow-1"></div>

        {{-- Sağ: PDF butonu (masaüstü) --}}
        <a href="{{ route('contacts.pdf', request()->only('company_id')) }}"
           class="btn btn-outline-secondary btn-sm pdf-btn d-none d-md-flex align-items-center justify-content-center">
          <i class="fas fa-file-pdf me-2"></i> PDF&nbsp;İndir
        </a>
      </div>

      {{-- ==================== FİLTRE FORMU ==================== --}}
      <form method="GET" class="px-3 py-2 border-bottom">
        <div class="row g-2">
          <div class="col-sm-4 col-md-3 col-lg-2">
            <label class="form-label mb-0 small">Firma</label>
            <select name="company_id" class="form-select"
                    onchange="this.form.submit()">
              <option value="">Tüm Firmalar</option>
              @foreach ($companies as $c)
                <option value="{{ $c->id }}" @selected($c->id == request('company_id'))>
                  {{ $c->company_name }}
                </option>
              @endforeach
            </select>
          </div>
          {{-- İleride başka filtre eklemek isterseniz ayni satıra ekleyebilirsiniz --}}
        </div>
      </form>

      {{-- Mobilde PDF butonu --}}
      <div class="d-md-none px-3 pt-3">
        <a href="{{ route('contacts.pdf', request()->only('company_id')) }}"
           class="btn btn-outline-secondary w-100 pdf-btn d-flex justify-content-center">
          <i class="fas fa-file-pdf me-2"></i> PDF&nbsp;İndir
        </a>
      </div>

      <div class="card-body p-0">
        {{-- =================== Masaüstü TABLO =================== --}}
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
              @forelse ($contacts as $ct)
                <tr>
                  <td>{{ $ct->name }}</td>
                  <td>{{ $ct->position }}</td>
                  <td>{{ $ct->email }}</td>
                  <td>{{ $ct->phone }}</td>
                  <td>{{ $ct->company?->company_name ?? '—' }}</td>
                  <td>{{ $ct->updated_at->format('d.m.Y H:i') }}</td>
                  <td>
                    <a href="{{ route('contacts.show', $ct) }}" class="btn btn-sm btn-info">Gör</a>
                    <a href="{{ route('contacts.edit', $ct) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('contacts.destroy', $ct) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">Kayıtlı kişi bulunamadı.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- =================== MOBİL KARTLAR =================== --}}
        <div class="mobile-cards p-3">
          @forelse ($contacts as $ct)
            <div class="card shadow-sm p-3 mb-3">
              <p><strong>Ad:</strong> {{ $ct->name }}</p>
              <p><strong>Pozisyon:</strong> {{ $ct->position }}</p>
              <p><strong>E-posta:</strong> {{ $ct->email }}</p>
              <p><strong>Telefon:</strong> {{ $ct->phone }}</p>
              <p><strong>Firma:</strong> {{ $ct->company?->company_name ?? '—' }}</p>
              <p><strong>Güncellenme:</strong> {{ $ct->updated_at->format('d.m.Y H:i') }}</p>
              <div class="mt-2">
                <a href="{{ route('contacts.show', $ct) }}" class="btn btn-sm btn-info">Gör</a>
                <a href="{{ route('contacts.edit', $ct) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('contacts.destroy', $ct) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-center text-muted">Kayıtlı kişi bulunamadı.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  /* Header ortalama */
  .card-header{min-height:56px}
  .header-center{
      position:absolute;
      left:50%;top:50%;
      transform:translate(-50%,-50%);
  }

  /* PDF butonu */
  .pdf-btn{min-width:150px}

  /* Masaüstü ↔︎ Mobil */
  .mobile-cards{display:none}
  @media(max-width:768px){
      .desktop-table{display:none}
      .mobile-cards{display:block}
  }

  /* Uzun metinler */
  .table td,.table th{
      white-space:normal!important;
      word-break:break-word;
  }
</style>
@endpush
