@extends('layouts.app')
@section('title','Firma Düzenle')

@section('content')
<div class="container">
  <h1>Edit Company #{{ $company->id }}</h1>
  <form action="{{ route('companies.update',$company) }}" method="POST">
    @csrf @method('PUT')
    

    <div class="form-group">
      <label for="Company_name">Firma Adı</label>
      <input type="text" class="form-control" id="Company_name" name="Company_name"
             value="{{ old('Company_name',$company->company_name) }}" required>
    </div>

    <!-- Diğer alanlar, create ile aynı -->
    <!-- ... -->

    <button type="submit" class="btn btn-primary">Güncelle</button>
    <a href="{{ route('companies.index') }}" class="btn btn-secondary">İptal</a>
  </form>
</div>
@endsection
