@php
  use App\Models\SupportRequest;
  use App\Models\Reminder;
  use Illuminate\Support\Facades\Auth;

  $pendingCount  = SupportRequest::where('customer_id', Auth::user()->customer_id)
                                 ->where('situation', 'pending')
                                 ->count();

  $reminderCount = Reminder::where('customer_id', Auth::user()->customer_id)
                           ->whereDate('reminder_date', today())
                           ->count();
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Logo ve Başlık -->
  <a href="{{ route('dashboard.index') }}" class="brand-link d-flex align-items-center" style="gap:10px;">
    <div style="background:#fff;border-radius:50%;padding:6px;width:45px;height:45px;display:flex;align-items:center;justify-content:center;">
      <img src="{{ asset('images/ika_crm-Photoroom.jpg') }}" alt="Logo" style="max-height:28px;max-width:28px;">
    </div>
    <span class="brand-text" style="font-weight:600;font-size:16px;color:#f1f1f1;letter-spacing:0.5px;font-family:inherit;">
      IKA CRM SİSTEMİ
    </span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Kontrol Paneli</p>
          </a>
        </li>

        <!-- Companies -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Şirketler <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('companies.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Şirket Ekle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('companies.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Şirketleri Görüntüle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('contacts.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Kişileri Görüntüle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('contacts.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Kişi Ekle</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Orders -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>Siparişler <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('orders.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Sipariş Ekle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('orders.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Siparişleri Görüntüle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('offers.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Teklifleri Görüntüle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('offers.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Teklif Ekle</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Products -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-box-open"></i>
            <p>Ürünler <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('products.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Ürün Ekle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('products.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Ürünleri Görüntüle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product_stocks.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Stok Durumu</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product_prices.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Ürün Fiyatları</p>
              </a>
            </li>

            {{-- ↓ Seri Numaraları menüsü ↓ --}}
            <li class="nav-item">
              <a href="{{ route('product_serials.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Seri Numaraları Görüntüle</p>
              </a>
            </li>
           
            {{-- ↑ Seri Numaraları menüsü ↑ --}}
            
          </ul>
        </li>

        <!-- Accounts -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>Hesaplar <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('accounts.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Hesapları Görüntüle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('movements.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Hareket Ekle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('movements.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Hareketleri Görüntüle</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Raporlar <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('reports.sales') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Satış Raporu</p>
              </a>
            </li>
            
            <li class="nav-item">
              <a href="{{ route('reports.product_stock') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Stok Raporu</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reports.account_summary') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Hesap Özeti</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reports.support') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Destek Talepleri</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Support -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-headset"></i>
            <p>Destek <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('support.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Destek Talebi Oluştur</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('support.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Tüm Talepler</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('support.pending') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Bekleyen Talepler
                  @if($pendingCount)
                    <span class="badge badge-danger right">{{ $pendingCount }}</span>
                  @endif
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('support.resolved') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Çözülen Talepler</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Actions -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Faaliyetler <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('actions.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Faaliyet Ekle</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('actions.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i><p>Tüm Faaliyetler</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reminders -->
<li class="nav-item has-treeview">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-bell"></i>
    <p>Hatırlatmalar <i class="right fas fa-angle-left"></i></p>
  </a>
  <ul class="nav nav-treeview">
    <li class="nav-item">
      <a href="{{ route('reminders.create') }}" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Hatırlatma Ekle</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('reminders.index') }}" class="nav-link">
        <!-- Eski ikona döndük -->
        <i class="far fa-circle nav-icon"></i>
        <p>
          Hatırlatmaları Görüntüle
          @if($reminderCount)
            <span class="badge badge-danger right">{{ $reminderCount }}</span>
          @endif
        </p>
      </a>
    </li>
  </ul>
</li>


      </ul>
    </nav>
  </div>
</aside>
