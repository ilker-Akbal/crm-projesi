@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
  
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">İşlemler</h3>
        <div>
          <a href="{{ route('actions.create') }}" class="btn btn-sm btn-primary">Yeni İşlem</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                
                <th>Müşteri</th>
                <th>Kullanıcı</th>
                <th>Tür</th>
                <th>Tarih</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($actions as $a)
                <tr>
                  
                  <td>{{ $a->customer->customer_name }}</td>
                  <td>{{ $a->user->username }}</td>
                  <td>{{ $a->action_type }}</td>
                  <td>{{ $a->action_date }}</td>
                  <td>
                    <a href="{{ route('actions.show', $a) }}" class="btn btn-sm btn-info">Gör</a>
                    <a href="{{ route('actions.edit', $a) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('actions.destroy', $a) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center">Kayıtlı işlem bulunamadı.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
