@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
 
    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Destek Talepleri</h3>
        <a href="{{ route('support.create') }}" class="btn btn-sm btn-primary">Yeni Talep</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                
                <th>Müşteri</th>
                <th>Başlık</th>
                <th>Durum</th>
                <th>Kayıt Tarihi</th>
                <th>Son Güncelleme</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($supports as $s)
                <tr>
                  
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->title }}</td>
                  <td class="text-capitalize">{{ $s->situation }}</td>
                  <td>{{ $s->registration_date }}</td>
                  <td>{{ $s->updated_at->format('Y-m-d') }}</td>
                  <td>
                    <a href="{{ route('support.show', $s) }}" class="btn btn-sm btn-info">Görüntüle</a>
                    <a href="{{ route('support.edit', $s) }}" class="btn btn-sm btn-warning">Düzenle</a>
                    <form action="{{ route('support.destroy', $s) }}" method="POST" class="d-inline">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Talep bulunamadı.</td>
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
