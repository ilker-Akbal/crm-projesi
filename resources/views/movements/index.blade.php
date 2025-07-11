@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">

      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cari Hareketler</h3>
        <a href="{{ route('movements.create') }}" class="btn btn-sm btn-primary">Hareket Ekle</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Hesap</th>
                <th>Tarih</th>
                <th>Tutar</th>
                <th>Tip</th>
                <th>Açıklama</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($movements as $m)
                <tr>
                  <td>{{ $m->id }}</td>
                  <td>{{ $m->currentCard?->customer?->customer_name }}</td>
                  <td>{{ $m->departure_date }}</td>
                  <td>{{ number_format($m->amount,2) }}</td>
                  <td>{{ $m->movement_type }}</td>
                  <td>{{ $m->explanation }}</td>
                  <td>—</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Kayıt bulunamadı</td>
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
