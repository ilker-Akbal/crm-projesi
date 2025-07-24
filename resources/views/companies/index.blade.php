@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Firmalar</h3>
        <a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">Firma Ekle</a>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>Firma Adı</th>
              <th>Vergi Numarası</th>
              <th>Telefon</th>
              <th>E-posta</th>
              <th>Müşteri Adı</th>
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
              <td>{{ $c->customer?->customer_name ?? 'Müşteri Yok' }}</td>
              <td>
                @switch($c->current_role)
                  @case('admin') Yönetici @break
                  @case('user') Kullanıcı @break
                  @case('customer') Müşteri @break
                  @default Belirtilmemiş
                @endswitch
              </td>
              <td>
                {{ \Carbon\Carbon::parse($c->registration_date)->format('d.m.Y') }}
              </td>
              <td>
                <a href="{{ route('companies.show',$c) }}" class="btn btn-sm btn-info">Gör</a>
                <a href="{{ route('companies.edit',$c) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('companies.destroy',$c) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center">Kayıtlı firma bulunamadı.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
