@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <!-- Kart Başlığı + Hızlı Arama -->
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Support Request Report</h3>
        <div class="ml-auto">
          <input type="text" id="supportSearch" class="form-control" placeholder="Search requests..." style="max-width: 250px;">
        </div>
      </div>

      <!-- Tablo -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="supportTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Title</th>
                <th>Status</th>
                <th>Registered Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($requests as $r)
                <tr>
                  <td>{{ $r->id }}</td>
                  <td>{{ $r->customer->customer_name }}</td>
                  <td>{{ $r->title }}</td>
                  <td>{{ $r->situation }}</td>
                  <td>{{ $r->registration_date }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center p-4">No data found</td>
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
