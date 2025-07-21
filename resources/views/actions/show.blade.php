@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Action Detayı</h3></div>

      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">Kayıt Sahibi (User)</dt>
          <dd class="col-sm-9">{{ $action->user->username }}</dd>

          <dt class="col-sm-3">Bağlı Müşteri</dt>
          <dd class="col-sm-9">{{ $action->customer->customer_name }}</dd>

          <dt class="col-sm-3">İşlem Türü</dt>
          <dd class="col-sm-9">{{ $action->action_type }}</dd>

          <dt class="col-sm-3">Tarih</dt>
          <dd class="col-sm-9">{{ $action->action_date->format('d.m.Y') }}</dd>

          <dt class="col-sm-3">Son Güncelleyen</dt>
          <dd class="col-sm-9">
              {{ optional($action->updatedBy)->username ?? '-' }}
          </dd>
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