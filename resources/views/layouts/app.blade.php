<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'CRM Panel')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- AdminLTE & FontAwesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  {{-- ===== Navbar & Sidebar sadece oturum açıksa ===== --}}
 @unless(request()->segment(1) === 'admin')
    {{-- Normal CRM navbar ve sidebar --}}
    @include('layouts.navbar')
    @include('layouts.sidebar')
  @endunless

  {{-- ------- İçerik -------- --}}
  <div class="content-wrapper">
    @yield('content')
  </div>

</div>

<!-- AdminLTE & Bootstrap JS -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

{{-- Proje JS --}}
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
