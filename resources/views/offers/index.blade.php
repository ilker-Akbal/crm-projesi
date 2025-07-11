@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Teklifler</h3>
        <a href="{{ route('offers.create') }}" class="btn btn-sm btn-primary">Teklif Ekle</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="offers-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Müşteri</th>
                <th>Teklif Tarihi</th>
                <th>Geçerlilik</th>
                <th>Durum</th>
                <th>Toplam</th>
                <th>İşlemler</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($offers as $o)
                <tr>
                  <td>{{ $o->id }}</td>
                  <td>{{ $o->customer?->customer_name }}</td>
                  <td>{{ $o->offer_date }}</td>
                  <td>{{ $o->valid_until }}</td>
                  <td>{{ $o->status }}</td>
                  <td>{{ number_format($o->total_amount ?? 0, 2) }}</td>
                  <td>
                    <a href="{{ route('offers.show',$o) }}"  class="btn btn-sm btn-info">Görüntüle</a>
                    <a href="{{ route('offers.edit',$o) }}"  class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('offers.destroy',$o) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">Kayıt bulunamadı</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
