<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'IKA CRM Paneli')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  @stack('styles')

  <style>
    /* Admin (top-nav) layout’ta sidebar boşluğu bırakma */
    .layout-top-nav .content-wrapper { margin-left: 0 !important; }
    .layout-top-nav .main-sidebar   { display: none !important; }
    .content-wrapper { padding-top: .5rem; }
  </style>

  <script>
    // BODY class'ını sayfa daha yüklenmeden URL'e göre normalize et
    (function () {
      var isAdmin = location.pathname.startsWith('/admin');
      var cls = 'hold-transition ' + (isAdmin ? 'layout-top-nav' : 'sidebar-mini layout-fixed');
      document.addEventListener('DOMContentLoaded', function () {
        var b = document.body;
        b.classList.remove('layout-top-nav','sidebar-mini','layout-fixed','sidebar-open','sidebar-collapse','control-sidebar-slide-open');
        cls.split(' ').forEach(function(c){ if(c) b.classList.add(c); });
      });
    })();
  </script>
</head>

@php
  $isAdminArea = request()->is('admin*');
@endphp

<body
  data-layout="{{ $isAdminArea ? 'admin' : 'app' }}"
  class="hold-transition {{ auth()->check() ? ($isAdminArea ? 'layout-top-nav' : 'sidebar-mini layout-fixed') : '' }}"
>
  @auth
    <div class="wrapper">
      {{-- Navbar --}}
      @include('layouts.navbar', [
        'navAlignment' => $isAdminArea ? 'justify-content-start' : 'justify-content-center',
        'isAdminArea'  => $isAdminArea,
      ])

      {{-- Sidebar sadece admin dışı --}}
      @unless($isAdminArea)
        @include('layouts.sidebar')
      @endunless

      <div class="content-wrapper">
        @if($isAdminArea)
          <div class="content">
            <div class="container">
              @yield('content')
            </div>
          </div>
        @else
          @yield('content')
        @endif
      </div>
    </div>
  @endauth

  @guest
    @yield('content')
  @endguest

  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>

  <script>
    // Güvenlik: Admin sayfasında kalan sidebar sınıflarını her yüklemede temizle
    (function(){
      var b = document.body;
      if (location.pathname.startsWith('/admin')) {
        b.classList.remove('sidebar-open','sidebar-collapse','control-sidebar-slide-open');
      }
    })();
  </script>

  @stack('scripts')
</body>
</html>
