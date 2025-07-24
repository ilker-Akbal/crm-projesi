@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Kişiler</h3>
        <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">Kişi Ekle</a>
      </div>
      <div class="card-body p-0">
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
            @forelse($contacts as $ct)
            <tr>
              <td>{{ $ct->name }}</td>
              <td>{{ $ct->position }}</td>
              <td>{{ $ct->email }}</td>
              <td>{{ $ct->phone }}</td>
              <td>{{ $ct->company?->company_name }}</td>
              <td>{{ $ct->updated_at }}</td>
              <td>
                <a href="{{ route('contacts.show',$ct) }}" class="btn btn-sm btn-info">Gör</a>
                <a href="{{ route('contacts.edit',$ct) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('contacts.destroy',$ct) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button onclick="return confirm('Silinsin mi?')" class="btn btn-sm btn-danger">Sil</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center">Kayıtlı kişi bulunamadı.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
