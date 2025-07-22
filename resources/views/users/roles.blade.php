@extends('layouts.app')

@section('content')
<section class="content">
  <div class="container-fluid">
    <div class="card card-outline card-info">
      <div class="card-header"><h3 class="card-title">Rol Listesi</h3></div>
      <div class="card-body p-0">
        <ul class="list-group list-group-flush">
          @forelse ($roles as $r)
            <li class="list-group-item">{{ ucfirst($r) }}</li>
          @empty
            <li class="list-group-item text-center">Tanımlı rol yok.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</section>
@endsection
