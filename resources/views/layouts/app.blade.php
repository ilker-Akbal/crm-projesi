<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'IKA CRM Paneli')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AdminLTE & FontAwesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  @stack('styles')
</head>

@php
  // /admin* URL’lerinde sidebar/layout hiç gelmesin
  $isAdminArea = request()->is('admin*');
@endphp

<body class="hold-transition {{ auth()->check() && ! $isAdminArea ? 'sidebar-mini layout-fixed' : '' }}">

  @auth
    <div class="wrapper">
      {{-- Üst Menü (Navbar) her zaman gösterilir --}}
      @include('layouts.navbar', [
        'navAlignment' => $isAdminArea ? 'justify-content-start' : 'justify-content-center'
      ])

      {{-- Admin olmayan sayfalarda sol menü (Sidebar) gösterilir --}}
      @unless($isAdminArea)
        @include('layouts.sidebar')
      @endunless

      <div class="content-wrapper">
        @yield('content')
      </div>
    </div>
  @endauth

  @guest
    {{-- Giriş yapmamış kullanıcılar için içerik --}}
    @yield('content')
  @endguest

  <!-- AdminLTE & Bootstrap JS -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>