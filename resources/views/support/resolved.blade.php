@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-success card-outline">
      <div class="card-header"><h3 class="card-title">Çözülmüş Talepler</h3></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                
                <th>Müşteri</th>
                <th>Başlık</th>
                <th>Çözülme Tarihi</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($supports as $s)
                <tr>
                  
                  <td>{{ $s->customer->customer_name }}</td>
                  <td>{{ $s->title }}</td>
                  <td>{{ $s->updated_at->format('Y-m-d') }}</td>
                  <td>
                    <a href="{{ route('support.show', $s) }}" class="btn btn-sm btn-info">Görüntüle</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Çözülmüş talep bulunamadı.</td>
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
