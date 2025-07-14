@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-info">
      <div class="card-header"><h3 class="card-title">Reminder Details</h3></div>
      <div class="card-body">
        <p><strong>ID:</strong> {{ $reminder->id }}</p>
        <p><strong>Title:</strong> {{ $reminder->title }}</p>
        <p><strong>Date:</strong> {{ $reminder->reminder_date }}</p>
        <p><strong>Customer:</strong> {{ $reminder->customer->customer_name }}</p>
        <p><strong>User:</strong> {{ $reminder->user->username }}</p>
        <p><strong>Explanation:</strong> {!! nl2br(e($reminder->explanation)) !!}</p>
      </div>
      <div class="card-footer">
        <a href="{{ route('reminders.index') }}" class="btn btn-secondary">Back</a>
      </div>
    </div>
  </div>
</section>
@endsection
