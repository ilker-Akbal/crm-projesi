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
    {{-- Yıldönümü Hatırlatmaları (Kırmızı Takvim + Siyah Yazılı Badge) --}}
    @php
      use App\Models\Company;
      use Carbon\Carbon;

      $anniversaryCount = Company::where('customer_id', auth()->user()->customer_id ?? null)
        ->whereNotNull('foundation_date')
        ->get()
        ->filter(fn($company) => Carbon::parse($company->foundation_date)->format('m-d') === now()->format('m-d'))
        ->count();
    @endphp

    <li class="nav-item">
      <a class="nav-link position-relative d-flex align-items-center gap-1" 
         href="{{ route('reminders.index') }}" 
         title="Bugün Kutlamalar Var">
        {{-- Takvim ikonu kırmızı --}}
        <i class="fas fa-calendar-day" style="font-size:1.4rem; color:##82CDFF; margin-left:4px;"></i>

        {{-- Eğer yıldönümü varsa, siyah yazılı badge --}}
        @if($anniversaryCount > 0)
          <span class="badge navbar-badge" style="
              background-color: #facc15;
              color: #000;
              font-weight: bold;
              font-size: 0.7rem;
              top: 0;
              right: 0;
              border-radius: 8px;
          ">
            {{ $anniversaryCount }}
          </span>
        @endif
      </a>
    </li>

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
