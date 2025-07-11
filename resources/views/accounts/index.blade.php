@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">

      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cari Hesaplar</h3>
        <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-primary">Hesap Aç</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Müşteri</th>
                <th>Açılış Bakiyesi</th>
                <th>Açılış Tarihi</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($accounts as $acc)
                <tr>
                  <td>{{ $acc->id }}</td>
                  <td>{{ $acc->customer?->customer_name }}</td>
                  <td>{{ number_format($acc->balance, 2) }}</td>
                  <td>{{ $acc->opening_date }}</td>
                  <td>
                    <a href="{{ route('accounts.edit', $acc) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('accounts.destroy', $acc) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Kayıt bulunamadı</td>
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
