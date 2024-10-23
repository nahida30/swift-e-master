@section('sidebar')
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard.index') }}">
        <i class="bi bi-grid"></i><span>Dashboard</span>
      </a>
    </li>
    @if($user->isAdmin())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('requests') || request()->is('requests/*') ? '' : 'collapsed' }}" href="{{ route('requests.index') }}">
        <i class="bi bi-layout-text-window-reverse"></i>
        <span>Requests</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->is('invoices') ? '' : 'collapsed' }}" href="{{ route('invoices.index') }}">
        <i class="bi bi-envelope"></i>
        <span>Invoices</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->is('clients') || request()->is('clients/*') ? '' : 'collapsed' }}" href="{{ route('clients.index') }}">
        <i class="bi bi-layout-text-window-reverse"></i>
        <span>Clients</span>
      </a>
    </li>
    @if($user->role == 'admin')
    <li class="nav-item">
      <a class="nav-link {{ request()->is('users') || request()->is('users/*') ? '' : 'collapsed' }}" href="{{ route('users.index') }}">
        <i class="bi bi-layout-text-window-reverse"></i>
        <span>Users</span>
      </a>
    </li>
    @endif
    @endif
    @if(!$user->isAdmin())
    <li class="nav-item">
      <a class="nav-link {{ request()->is('documents') || request()->is('documents/*') ? '' : 'collapsed' }}" href="{{ route('documents.index') }}">
        <i class="bi bi-journal-text"></i><span>Documents</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link justify-content-between {{ request()->is('invoices') ? '' : 'collapsed' }}" href="{{ route('invoices.index') }}">
        <div>
          <i class="bi bi-envelope"></i>
          <span>Invoices</span>
        </div>
        @if(count($notice_invoices))
        <span class="badge rounded-pill bg-danger">
          {{ count($notice_invoices) }}
          <span class="visually-hidden">pending invoices</span>
        </span>
        @endif
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->is('pricing') ? '' : 'collapsed' }}" href="{{ route('pricing.index') }}">
        <i class="bi bi-menu-button-wide"></i>
        <span>Pricing</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->is('aboutus') ? '' : 'collapsed' }}" href="{{ route('aboutus.index') }}">
        <i class="bi bi-question-circle"></i><span>About Us</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ request()->is('helpful-videos') ? '' : 'collapsed' }}" href="{{ route('helpful.videos') }}">
        <i class="bi bi-camera-video"></i><span>Helpful Videos</span>
      </a>
    </li>
    @endif
  </ul>
</aside>
@endsection