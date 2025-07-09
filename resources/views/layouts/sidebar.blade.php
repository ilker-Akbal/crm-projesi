<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link">
    <span class="brand-text font-weight-light">CRM Panel</span>
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
          <a href="{{ route('dashboard') }}" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Companies with dropdown -->
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
              <a href="{{ route('companies.index') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Company</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Companies</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>View Contacts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Contact</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Diğer menüler aynı kaldı -->
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
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Order</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Order View</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>View Offer</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="far fa-circle nav-icon"></i>
        <p>Add Offer</p>
      </a>
    </li>
  </ul>
</li>
        <li class="nav-item">
          <a href="{{ route('products.index') }}" class="nav-link">
            <i class="nav-icon fas fa-box-open"></i>
            <p>Products</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('accounts.index') }}" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>Accounts</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('reports.index') }}" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Reports</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('support.index') }}" class="nav-link">
            <i class="nav-icon fas fa-headset"></i>
            <p>Support</p>
          </a>
        </li>
        <li class="nav-item">
  <a href="#" class="nav-link">
    <i class="nav-icon fas fa-tasks"></i>
    <p>Actions</p>
  </a>
</li>
<li class="nav-item">
  <a href="" class="nav-link">
    <i class="nav-icon fas fa-user"></i>
    <p>Customers</p>
  </a>
</li>

        <li class="nav-item">
          <a href="{{ route('users.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('reminders.index') }}" class="nav-link">
            <i class="nav-icon fas fa-bell"></i>
            <p>Reminders</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
