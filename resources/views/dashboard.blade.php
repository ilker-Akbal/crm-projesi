@extends('layouts.app')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body text-center">
                        <h1>Welcome to the IKA CRM Panel</h1>
                        <p>Select a module from the sidebar to get started.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif