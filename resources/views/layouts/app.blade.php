<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'IKA CRM Panel')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AdminLTE & FontAwesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  
  <!-- BEYAZ BOŞLUĞU KALDIRAN STİLLER -->
  <style>
    :root {
      --sidebar-width: 250px;
    }
    
    body {
      overflow-x: hidden;
      min-height: 100vh;
      background-color: #f4f6f9;
    }
    
    .wrapper {
      display: flex;
      min-height: 100vh;
      position: relative;
    }
    
    .main-sidebar {
      position: fixed;
      height: 100vh;
      width: var(--sidebar-width);
      z-index: 1038;
      left: 0;
      transition: transform .3s ease-in-out;
    }
    
    .content-wrapper {
      flex: 1;
      margin-left: var(--sidebar-width);
      transition: margin-left .3s ease-in-out;
      min-height: 100vh;
      background-color: #f4f6f9;
      padding-top: calc(3.5rem + 1px);
    }
    
    .main-header {
      position: fixed;
      width: 100%;
      z-index: 1030;
      left: 0;
      padding-left: var(--sidebar-width);
      transition: padding-left .3s ease-in-out;
    }
    
    /* Sidebar kapalıyken */
    .sidebar-collapse .main-sidebar {
      transform: translateX(-100%);
    }
    
    .sidebar-collapse .content-wrapper {
      margin-left: 0;
    }
    
    .sidebar-collapse .main-header {
      padding-left: 0;
      width: 100%;
    }
    
    /* Admin alanı için özel stil */
    .admin-area .content-wrapper,
    .admin-area .main-header {
      margin-left: 0;
      padding-left: 0;
    }
    
    @media (max-width: 992px) {
      .content-wrapper,
      .main-header {
        margin-left: 0;
        padding-left: 0;
      }
      
      .main-sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar-open .main-sidebar {
        transform: translateX(0);
      }
    }
  </style>
  
  @stack('styles')
</head>

@php
  // /admin* URL'lerinde sidebar/layout hiç gelmesin
  $isAdminArea = request()->is('admin*');
@endphp

<body class="hold-transition {{ auth()->check() && ! $isAdminArea ? 'sidebar-mini layout-fixed' : '' }} {{ $isAdminArea ? 'admin-area' : '' }}">

  @auth
    <div class="wrapper">
      {{-- Navbar her zaman --}}
      @include('layouts.navbar', [
        'navAlignment' => $isAdminArea ? 'justify-content-start' : 'justify-content-center'
      ])

      {{-- Admin olmayan sayfalarda sidebar --}}
      @unless($isAdminArea)
        @include('layouts.sidebar')
      @endunless

      <div class="content-wrapper">
        @yield('content')
      </div>
    </div>
  @endauth

  @guest
    {{-- Guest yani login sayfası --}}
    @yield('content')
  @endguest

  <!-- AdminLTE & Bootstrap JS -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  
  <!-- Sidebar toggle için ek script -->
  <script>
    $(document).ready(function() {
      // PushMenu için özel ayar
      $('[data-widget="pushmenu"]').on('click', function() {
        $('body').toggleClass('sidebar-collapse');
        $('body').toggleClass('sidebar-open');
      });
      
      // Admin alanında navbar pozisyonu
      @if($isAdminArea)
        $('.main-header').css('position', 'static');
      @endif
    });
  </script>
  
  @stack('scripts')
</body>
</html>