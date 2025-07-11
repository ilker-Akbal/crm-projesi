@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">İşlemler (Actions)</h3>
        <a href="{{ route('actions.create') }}" class="btn btn-sm btn-primary">Yeni İşlem</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Müşteri</th>
                <th>Kullanıcı</th>
                <th>İşlem Türü</th>
                <th>Tarih</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($actions as $a)
                <tr>
                  <td>{{ $a->id }}</td>
                  <td>{{ $a->customer?->customer_name }}</td>
                  <td>{{ $a->user?->username }}</td>
                  <td>{{ $a->action_type }}</td>
                  <td>{{ $a->action_date }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center">Kayıt bulunamadı.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
