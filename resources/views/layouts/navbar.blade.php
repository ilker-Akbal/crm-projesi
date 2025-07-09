<!-- resources/views/layouts/navbar.blade.php -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Sol navbar bağlantıları -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Anasayfa</a>
    </li>

    <!-- Dashboard Dropdown Menüsü -->
    <li class="nav-item dropdown d-none d-sm-inline-block">
      <a id="dashboardDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
        Dashboard
      </a>
      <div class="dropdown-menu" aria-labelledby="dashboardDropdown">
        <a href="#" class="dropdown-item">Firmalar</a>
        <a href="#" class="dropdown-item">Siparişler</a>
        <a href="#" class="dropdown-item">Ürünler</a>
        <a href="#" class="dropdown-item">Cari İşlemler</a>
        <a href="#" class="dropdown-item">Raporlar</a>
        <a href="#" class="dropdown-item">Destek</a>
        <a href="#" class="dropdown-item">Kullanıcılar</a>
        <a href="#" class="dropdown-item">Hatırlatmalar</a>
      </div>
    </li>
  </ul>
</nav>
