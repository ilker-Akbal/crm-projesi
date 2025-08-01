{{-- resources/views/accounts/index.blade.php (Gör butonu genişletildi) --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">

      <div class="card-header">
        <h3 class="card-title">Hesaplar</h3>
      </div>

      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Müşteri</th>
              <th>Açılış Tarihi</th>
              <th style="width:120px" class="text-center">İşlem</th>
            </tr>
          </thead>
          <tbody>
            @forelse($accounts as $acc)
              <tr>
                <td>{{ $acc->customer->customer_name }}</td>
                <td>{{ \Carbon\Carbon::parse($acc->opening_date)->format('d.m.Y') }}</td>
                <td class="text-center">
                  <a href="{{ route('accounts.show', $acc) }}" class="btn btn-sm btn-info w-100">Gör</a>
                </td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center">Kayıtlı hesap bulunamadı.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
  </div>
</section>
@endsection
