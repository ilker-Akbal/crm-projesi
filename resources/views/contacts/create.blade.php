@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Kişi Ekle</h3>
      </div>
      <form action="{{ route('contacts.store') }}" method="POST">
        @csrf
        <div class="card-body">
          
          
          <div class="form-group">
            <label for="company_id">Firma</label>
            <select name="company_id" id="company_id" class="form-control">
              <option value="">-- seçiniz --</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}" {{ old('company_id')==$c->id ? 'selected' : '' }}>
                  {{ $c->company_name }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="form-group">
            <label for="name">Ad *</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name') }}" required>
          </div>
          
          <div class="form-group">
            <label for="position">Pozisyon</label>
            <input type="text" name="position" id="position" class="form-control"
                   value="{{ old('position') }}">
          </div>
          
          <div class="form-group">
            <label for="email">E-posta</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email') }}">
          </div>
          
          <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="{{ old('phone') }}">
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('contacts.index') }}" class="btn btn-secondary mr-2">İptal</a>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
