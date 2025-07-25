@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Hatırlatıcılar</h3>
        <a href="{{ route('reminders.create') }}" class="btn btn-sm btn-primary">Hatırlatıcı Ekle</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Başlık</th>
                <th>Tarih</th>
                <th>Açıklama</th>
                <th style="width:140px">İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reminders as $r)
                <tr>
                  <td>{{ $r->title }}</td>
                  <td>{{ $r->reminder_date }}</td>
                  <td>{{ Str::limit($r->explanation, 50) }}</td>
                  <td>
                    @if(Str::contains($r->title, 'Yıl Dönümü'))
                      <span style="
                        display:inline-block;
                        background-color:#facc15;
                        color:#000;
                        font-weight:bold;
                        padding:4px 8px;
                        border-radius:6px;
                        font-size:0.8rem;
                      ">
                        🎉 KUTLAMA
                      </span>
                    @else
                      <a href="{{ route('reminders.show', $r) }}" class="btn btn-xs btn-info">Görüntüle</a>
                      <a href="{{ route('reminders.edit', $r) }}" class="btn btn-xs btn-warning">Düzenle</a>
                      <form action="{{ route('reminders.destroy', $r) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-danger"
                                onclick="return confirm('Bu hatırlatıcıyı silmek istediğinize emin misiniz?')">
                          Sil
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">Hatırlatıcı bulunamadı.</td>
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
