{{-- resources/views/layouts/admin-navbar.blade.php --}}
<nav class="main-header navbar navbar-expand navbar-dark bg-dark">
  <!-- Left links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item">
      <a href="{{ url('/admin') }}" class="nav-link">Admin Anasayfa</a>
    </li>
  </ul>

  <!-- Right links -->
  <ul class="navbar-nav ml-auto">
    @auth
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user-cog"></i> {{ auth()->user()->username }}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="{{ route('admin.users.index') }}" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Kullanıcılar
          </a>
          <div class="dropdown-divider"></div>
          <form action="{{ route('admin.logout') }}" method="POST" class="dropdown-item p-0">
            @csrf
            <button class="btn btn-block text-left">
              <i class="fas fa-sign-out-alt mr-2"></i> Çıkış Yap
            </button>
          </form>
        </div>
      </li>
    @endauth
  </ul>
</nav>
