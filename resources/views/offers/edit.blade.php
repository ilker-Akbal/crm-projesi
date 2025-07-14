@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-primary">
      <div class="card-header"><h3 class="card-title">Edit Offer #{{ $offer->id }}</h3></div>
      <form action="{{ route('offers.update',$offer) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
          @include('partials.alerts')
          {{-- create.blade formunun aynısı, input değerlerini old(...,$offer->...) ile doldurun --}}
        </div>
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('offers.index') }}" class="btn btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
