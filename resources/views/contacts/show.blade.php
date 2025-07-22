@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Kişi #{{ $contact->id }} Detayları</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Ad:</strong> {{ $contact->name }}</li>
    <li class="list-group-item"><strong>Pozisyon:</strong> {{ $contact->position }}</li>
    <li class="list-group-item"><strong>E-posta:</strong> {{ $contact->email }}</li>
    <li class="list-group-item"><strong>Telefon:</strong> {{ $contact->phone }}</li>
    <li class="list-group-item"><strong>Firma:</strong> {{ $contact->company?->company_name }}</li>
    <li class="list-group-item"><strong>Güncellenme:</strong> {{ $contact->updated_at }}</li>
  </ul>
  <div class="mt-3">
    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Listeye Dön</a>
    <a href="{{ route('contacts.edit',$contact) }}" class="btn btn-warning">Düzenle</a>
  </div>
</div>
@endsection
