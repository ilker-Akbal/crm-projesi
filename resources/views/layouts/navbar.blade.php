{{-- resources/views/layouts/navbar.blade.php --}}
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  {{-- Sol kısım --}}
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('dashboard.index') }}" class="nav-link">Anasayfa</a>
    </li>
  </ul>

  {{-- Sağ kısım --}}
  <ul class="navbar-nav ml-auto">
    {{-- Hızlı Destek Talebi --}}
    <li class="nav-item">
      <a class="nav-link" href="{{ route('support.create') }}" title="Hızlı Destek Talebi">
        <i class="fas fa-headset" style="font-size:1.2rem; color:#17a2b8;"></i>
      </a>
    </li>

    {{-- Tam ekran --}}
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>

    {{-- Çıkış Yap --}}
    @auth
      <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-link nav-link">
            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
          </button>
        </form>
      </li>
    @endauth
  </ul>
</nav>
