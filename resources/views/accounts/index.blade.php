@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Hesaplar</h3>
        <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-primary">Hesap Ekle</a>
      </div>

      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Müşteri</th>
              <th>Açılış Tarihi</th>
              <th style="width:140px">İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse($accounts as $acc)
              <tr>
                <td>{{ $acc->id }}</td>
                <td>{{ $acc->customer->customer_name }}</td>
                <td>{{ \Carbon\Carbon::parse($acc->opening_date)->format('d.m.Y') }}</td>
                <td>
                  <a href="{{ route('accounts.show',  $acc) }}" class="btn btn-xs btn-info">Gör</a>
                  <a href="{{ route('accounts.edit',  $acc) }}" class="btn btn-xs btn-warning">Düzen</a>
                  <form action="{{ route('accounts.destroy', $acc) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Silinsin mi?')" class="btn btn-xs btn-danger">Sil</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center">Kayıtlı hesap bulunamadı.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</section>
@endsection
