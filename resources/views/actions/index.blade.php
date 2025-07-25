@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">İşlemler</h3>
        <a href="{{ route('actions.create') }}" class="btn btn-sm btn-primary">Yeni İşlem</a>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Kişi</th>
                <th>Tür</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th>Açıklama</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              @forelse($actions as $a)
                <tr>
                  <td>{{ $a->contact->name ?? '-' }}</td>
                  <td>
                    @switch($a->action_type)
                      @case('meeting') Toplantı @break
                      @case('call')    Telefon  @break
                      @case('email')   E-posta  @break
                      @case('visit')   Ziyaret  @break
                      @default         Diğer
                    @endswitch
                  </td>
                  <td>{{ \Carbon\Carbon::parse($a->action_date)->format('d.m.Y') }}</td>
                  <td>
                    @switch($a->status)
                      @case('potansiyel') <span class="badge badge-warning">Potansiyel</span> @break
                      @case('açık')       <span class="badge badge-success">Açık</span> @break
                      @case('kapalı')     <span class="badge badge-secondary">Kapalı</span> @break
                      @case('iptal')      <span class="badge badge-danger">İptal</span> @break
                      @default            <span class="badge badge-light">-</span>
                    @endswitch
                  </td>
                  <td>{{ Str::limit($a->description, 40) ?? '-' }}</td>
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
