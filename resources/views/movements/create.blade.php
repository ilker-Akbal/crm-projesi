@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">

      <div class="card-header"><h3 class="card-title">Cari Hareket Ekle</h3></div>

      <form action="{{ route('movements.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @include('partials.alerts')

          <div class="form-group">
            <label for="current_id">Hesap</label>
            <select name="current_id" id="current_id" class="form-control" required>
              <option value="">-- seçiniz --</option>
              @foreach ($accounts as $a)
                <option value="{{ $a->id }}" {{ old('current_id')==$a->id ? 'selected' : '' }}>
                  {{ $a->customer?->customer_name }} ({{ $a->id }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="departure_date">Tarih</label>
            <input  type="date"
                    name="departure_date"
                    id="departure_date"
                    class="form-control"
                    value="{{ old('departure_date', today()->toDateString()) }}"
                    required>
          </div>

          <div class="form-group">
            <label for="movement_type">Hareket Tipi</label>
            <select name="movement_type" id="movement_type" class="form-control" required>
              <option value="">-- seçiniz --</option>
              <option value="Debit"  {{ old('movement_type')=='Debit'  ? 'selected' : '' }}>Borç</option>
              <option value="Credit" {{ old('movement_type')=='Credit' ? 'selected' : '' }}>Alacak</option>
            </select>
          </div>

          <div class="form-group">
            <label for="amount">Tutar</label>
            <input  type="number"
                    step="0.01"
                    name="amount"
                    id="amount"
                    class="form-control"
                    value="{{ old('amount') }}"
                    required>
          </div>

          <div class="form-group">
            <label for="explanation">Açıklama</label>
            <textarea name="explanation" id="explanation" rows="3" class="form-control">{{ old('explanation') }}</textarea>
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
