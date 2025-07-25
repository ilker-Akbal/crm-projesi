@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">İşlem Detayı</h3>
      </div>

      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">İşlemi Yapan Kişi</dt>
          <dd class="col-sm-9">{{ $action->contact->name ?? '-' }}</dd>

          <dt class="col-sm-3">İşlem Türü</dt>
          <dd class="col-sm-9">
            @switch($action->action_type)
              @case('meeting') Toplantı @break
              @case('call')    Telefon  @break
              @case('email')   E-posta  @break
              @case('visit')   Ziyaret  @break
              @default         Diğer
            @endswitch
          </dd>

          <dt class="col-sm-3">Tarih</dt>
          <dd class="col-sm-9">{{ \Carbon\Carbon::parse($action->action_date)->format('d.m.Y') }}</dd>

          <dt class="col-sm-3">Durum</dt>
          <dd class="col-sm-9">
            @switch($action->status)
              @case('potansiyel') <span class="badge badge-warning">Potansiyel</span> @break
              @case('açık')       <span class="badge badge-success">Açık</span> @break
              @case('kapalı')     <span class="badge badge-secondary">Kapalı</span> @break
              @case('iptal')      <span class="badge badge-danger">İptal</span> @break
              @default            <span class="badge badge-light">Bilinmiyor</span>
            @endswitch
          </dd>

          <dt class="col-sm-3">Açıklama</dt>
          <dd class="col-sm-9">{!! nl2br(e($action->description ?? '-')) !!}</dd>
        </dl>
      </div>

      <div class="card-footer">
        <a href="{{ route('actions.edit', $action) }}" class="btn btn-warning">Düzenle</a>
        <a href="{{ route('actions.index') }}" class="btn btn-secondary">Listeye Dön</a>
      </div>
    </div>
  </div>
</section>
@endsection
