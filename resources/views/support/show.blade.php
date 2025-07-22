{{-- resources/views/support/show.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">


    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Destek Talebi Detayları</h3>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">ID</dt>
          <dd class="col-sm-9">{{ $support->id }}</dd>

          <dt class="col-sm-3">Müşteri</dt>
          <dd class="col-sm-9">{{ $support->customer->customer_name }}</dd>

          <dt class="col-sm-3">Başlık</dt>
          <dd class="col-sm-9">{{ $support->title }}</dd>

          <dt class="col-sm-3">Açıklama</dt>
          <dd class="col-sm-9">{{ $support->explanation ?? '-' }}</dd>

          <dt class="col-sm-3">Durum</dt>
          <dd class="col-sm-9">
            @if($support->situation === 'pending')
              <span class="badge badge-warning">Beklemede</span>
            @else
              <span class="badge badge-success">Çözüldü</span>
            @endif
          </dd>

          <dt class="col-sm-3">Kayıt Tarihi</dt>
          <dd class="col-sm-9">
            {{ \Carbon\Carbon::parse($support->registration_date)->format('d.m.Y') }}
          </dd>

          <dt class="col-sm-3">Son Güncelleme</dt>
          <dd class="col-sm-9">
            {{ $support->updated_at->format('d.m.Y H:i') }}
          </dd>
        </dl>
      </div>
      <div class="card-footer">
        <a href="{{ route('support.index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left me-1"></i> Listeye Dön
        </a>
        <a href="{{ route('support.edit', $support) }}" class="btn btn-primary">
          <i class="fas fa-edit me-1"></i> Düzenle
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
