@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Add Movement</h3></div>
      <form action="{{ route('movements.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="current_id">Account *</label>
            <select name="current_id" id="current_id" class="form-control" required>
              <option value="">-- select --</option>
              @foreach($accounts as $a)
                <option value="{{ $a->id }}" {{ old('current_id')==$a->id?'selected':'' }}>
                  {{ $a->customer->customer_name }} ({{ $a->id }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="departure_date">Date *</label>
            <input type="date" name="departure_date" id="departure_date"
                   class="form-control" value="{{ old('departure_date',today()->toDateString()) }}" required>
          </div>

          <div class="form-group">
            <label for="movement_type">Type *</label>
            <select name="movement_type" id="movement_type" class="form-control" required>
              <option value="">-- select --</option>
              <option value="Debit"  {{ old('movement_type')=='Debit'?'selected':'' }}>Debit</option>
              <option value="Credit" {{ old('movement_type')=='Credit'?'selected':'' }}>Credit</option>
            </select>
          </div>

          <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="number" step="0.01" name="amount" id="amount"
                   class="form-control" value="{{ old('amount') }}" required>
          </div>

          <div class="form-group">
            <label for="explanation">Explanation</label>
            <textarea name="explanation" id="explanation" rows="3"
                      class="form-control">{{ old('explanation') }}</textarea>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('movements.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
