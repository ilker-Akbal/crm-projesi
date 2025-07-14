@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Contact #{{ $contact->id }} Details</h2>
  <ul class="list-group">
    <li class="list-group-item"><strong>Name:</strong> {{ $contact->name }}</li>
    <li class="list-group-item"><strong>Position:</strong> {{ $contact->position }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $contact->email }}</li>
    <li class="list-group-item"><strong>Phone:</strong> {{ $contact->phone }}</li>
    <li class="list-group-item"><strong>Company:</strong> {{ $contact->company?->company_name }}</li>
    <li class="list-group-item"><strong>Updated At:</strong> {{ $contact->updated_at }}</li>
  </ul>
  <div class="mt-3">
    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('contacts.edit',$contact) }}" class="btn btn-warning">Edit</a>
  </div>
</div>
@endsection
