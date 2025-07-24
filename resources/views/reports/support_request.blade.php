@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <!-- Kart Başlığı + Hızlı Arama -->
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Destek Talep Raporu</h3>
        <div class="ml-auto">
          <input type="text" id="supportSearch" class="form-control" placeholder="Taleplerde ara..." style="max-width: 250px;">
        </div>
      </div>

      <!-- Tablo -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="supportTable">
            <thead>
              <tr>
                
                <th>Müşteri</th>
                <th>Başlık</th>
                <th>Durum</th>
                <th>Kayıt Tarihi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $r)
                <tr>
                  
                  <td>{{ $r->customer->customer_name }}</td>
                  <td>{{ $r->title }}</td>
                  <td>{{ $r->situation }}</td>
                  <td>{{ $r->registration_date }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center p-4">Veri bulunamadı</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Hızlı Arama (Tablo Filtreleme)
  document.getElementById('supportSearch').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#supportTable tbody tr');
    rows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(searchValue) ? '' : 'none';
    });
  });
});
</script>
@endpush
