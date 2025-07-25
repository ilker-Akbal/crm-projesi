@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-warning">
      <div class="card-header"><h3 class="card-title">Hatırlatıcı Düzenle</h3></div>
      <form action="{{ route('reminders.update', $reminder) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          <div class="form-group">
            <label for="title">Başlık *</label>
            <input type="text" name="title" id="title"
                   class="form-control" value="{{ old('title', $reminder->title) }}" required>
          </div>
          <div class="form-group">
            <label for="reminder_date">Hatırlatma Tarihi *</label>
            <input type="date" name="reminder_date" id="reminder_date"
                   class="form-control" value="{{ old('reminder_date', $reminder->reminder_date) }}" required>
          </div>
          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation" id="explanation" rows="3"
                      class="form-control">{{ old('explanation', $reminder->explanation) }}</textarea>
          </div>
        </div>
        <div class="card-footer">
          @include('partials.form-buttons')
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
