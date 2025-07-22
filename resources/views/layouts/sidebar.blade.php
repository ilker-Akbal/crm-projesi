<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Logo ve Başlık -->
  <a href="{{ route('dashboard.index') }}" class="brand-link d-flex align-items-center" style="gap: 10px;">
    <!-- Logo (circle arka plan) -->
    <div style="background-color: white; border-radius: 50%; padding: 6px; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
      <img src="{{ asset('images/ika_crm-Photoroom.jpg') }}" alt="Logo" style="max-height: 28px; max-width: 28px;">
    </div>
    <!-- Yazı (sidebar ile aynı font ama daha güçlü) -->
    <span class="brand-text" style="
        font-weight: 600;
        font-size: 16px;
        color: #f1f1f1;
        letter-spacing: 0.5px;
        font-family: inherit; /* Sidebar ile aynı font */
      ">
      IKA CRM SYSTEM
    </span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul 
        class="nav nav-pills nav-sidebar flex-column" 
        data-widget="treeview" 
        role="menu" 
        data-accordion="false"
      >
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard.index') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>



        <!-- Companies -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>
              Companies
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('companies.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Company</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('companies.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Companies</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('contacts.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Contacts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('contacts.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Contact</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Orders -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Orders
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('orders.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Order</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('orders.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Order View</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('offers.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Offer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('offers.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Offer</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Products -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-box-open"></i>
            <p>
              Products
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('products.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Product</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('products.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Products</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product_stocks.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Product Stocks</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('product_prices.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Product Prices</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Accounts -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>
              Accounts
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            
            <li class="nav-item">
              <a href="{{ route('accounts.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Accounts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('movements.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Movement</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('movements.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Movements</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('reports.sales') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reports.customers') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Customer Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reports.product_stock') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Product Stock Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reports.account_summary') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Current Account Summary</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reports.support') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Support Request Report</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Support -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-headset"></i>
            <p>
              Support
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('support.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Create Support Request</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('support.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Support Requests</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('support.pending') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pending Requests</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('support.resolved') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Resolved Requests</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Actions -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-tasks"></i>
            <p>
              Actions
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('actions.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Action</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('actions.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View All Actions</p>
              </a>
            </li>
           
          </ul>
        </li>

        <!-- Customers -->
{{-- ===================== Customers ===================== --}}



       {{-- ====================== Users ======================== --}}


        <!-- Reminders -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-bell"></i>
            <p>
              Reminders
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('reminders.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Reminder</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('reminders.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Reminders</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>
</aside>
