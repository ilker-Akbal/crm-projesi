@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-info">
      <div class="card-header"><h3 class="card-title">Hatırlatıcı Detayları</h3></div>
      <div class="card-body">
        <p><strong>Başlık:</strong> {{ $reminder->title }}</p>
        <p><strong>Tarih:</strong> {{ $reminder->reminder_date }}</p>
        <p><strong>Açıklama:</strong> {!! nl2br(e($reminder->explanation)) !!}</p>
      </div>
      <div class="card-footer">
        <a href="{{ route('reminders.index') }}" class="btn btn-secondary">Geri</a>
      </div>
    </div>
  </div>
</section>
@endsection
