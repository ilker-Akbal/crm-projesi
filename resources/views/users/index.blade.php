{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    
    <div class="card card-outline card-primary">
      <div class="card-header">
        <div class="d-flex justify-content-between mb-2">
          <a href="{{ url('/admin') }}" class="btn btn-sm btn-secondary">Geri</a>
          <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">Kullanıcı Ekle</a>
        </div>
        <h3 class="card-title">Kullanıcılar</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              
              <th>Kullanıcı Adı</th>
              <th>Rol</th>
              <th>Aktif</th>
              <th style="width:150px">İşlemler</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($users as $u)
              <tr>
                
                <td>{{ $u->username }}</td>
                <td>{{ ucfirst($u->role) }}</td>
                <td>
                  @if($u->active)
                    <span class="badge badge-success">Evet</span>
                  @else
                    <span class="badge badge-danger">Hayır</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.users.show', $u) }}" class="btn btn-xs btn-info">Görüntüle</a>
                  <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-xs btn-warning">Düzenle</a>
                  <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-danger"
                            onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">
                      Sil
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">Kullanıcı bulunamadı.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
@endsection
