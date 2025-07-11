@extends('layouts.app')

@section('content')
<section class="content">
 <div class="container-fluid">
  <div class="card card-primary card-outline">
   <div class="card-header"><h3 class="card-title">Create Support Request</h3></div>

   <form action="{{ route('support.store') }}" method="POST">
    @csrf
    <div class="card-body">

      {{-- Müşteri seçimi --}}
      <div class="form-group">
        <label for="customer_id">Customer</label>
        <select name="customer_id" id="customer_id" class="form-control">
          <option value="">-- select --</option>
          @foreach($customers as $c)
            <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
          @endforeach
        </select>
      </div>

      {{-- Başlık --}}
      <div class="form-group">
        <label for="title">Title</label>
        <input name="title" id="title" class="form-control" required>
      </div>

      {{-- Açıklama --}}
      <div class="form-group">
        <label for="explanation">Explanation</label>
        <textarea name="explanation" id="explanation" rows="4" class="form-control"></textarea>
      </div>

      {{-- Durum --}}
      <div class="form-group">
        <label for="situation">Status</label>
        <select name="situation" id="situation" class="form-control">
          <option value="pending">Pending</option>
          <option value="resolved">Resolved</option>
        </select>
      </div>

    </div>
    <div class="card-footer">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('support.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
   </form>
  </div>
 </div>
</section>
@endsection
