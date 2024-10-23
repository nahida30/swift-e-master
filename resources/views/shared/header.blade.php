@push('styles')
<style>

</style>
@endpush

@section('header')
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <div>
      <a href="{{ route('home') }}" class="logo d-flex align-items-center">
        <img src="{{ asset('img/logo192.png') }}" alt="logo" width="70" />
        <div class="d-none d-lg-block">
          <span class="d-block mb-0 fs-6">{{ config('app.name') }}</span>
          <small>TIME IS MONEY. SO SAVE IT</small>
        </div>
      </a>
    </div>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>
  <div class="search-bar">
    <!-- <form class="search-form d-flex align-items-center" method="POST">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword">
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form> -->
  </div>
  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          @if(count($notice_invoices) + count($notice_completed_requests))
          <span class="badge bg-primary badge-number" id="notice-count">{{ count($notice_invoices) + count($notice_completed_requests) }}</span>
          @endif
        </a>
        @if(count($notice_invoices) + count($notice_completed_requests))
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          @foreach($notice_invoices as $i => $notice_invoice)
          <a href="{{ route('invoices.index') }}">
            <li class="notification-item">
              <i class="bi bi-info-circle text-danger"></i>
              <div>
                <h4>Invoice Generated</h4>
                <p>Invoice generated for Requests @foreach($notice_invoice->requests as $i => $request)
                  {{ $request->tag_it }} #{{ $request->count }}{{ $i < count($notice_invoice->requests) - 1 ? ', ' : '' }}
                  @endforeach
                </p>
                <p>{{ $notice_invoice->created_at_local }}</p>
              </div>
            </li>
          </a>
          @if($i < count($notice_notes) - 1) <li>
            <hr class="dropdown-divider" />
      </li>
      @endif
      @endforeach
      @foreach($notice_completed_requests as $i => $notice_completed_request)
      <a href="{{ route('requests.index') }}">
        <li class="notification-item">
          <i class="bi bi-check-circle text-success"></i>
          <div>
            <h4>Request Completed</h4>
            <p>Request {{ $notice_completed_request->tag_it }} #{{ $notice_completed_request->count }} has been completed</p>
            <p>{{ $notice_invoice->created_at_local }}</p>
          </div>
        </li>
      </a>
      @if($i < count($notice_notes) - 1) <li>
        <hr class="dropdown-divider" />
        </li>
        @endif
        @endforeach
    </ul>
    @endif
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-chat-left-text"></i>
        @if(count($notice_notes))
        <span class="badge bg-success badge-number" id="notes-count">{{ count($notice_notes) }}</span>
        @endif
      </a>
      @if(count($notice_notes))
      <ul id="notes-dropdown" class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
        @foreach($notice_notes as $i => $notice_note)
        <li class="message-item position-relative">
          @if($notice_note->issue)
          <span class="position-absolute text-danger" style="right:1rem" data-toggle="tooltip" data-placement="top" title="Issue">
            <i class="bi bi-info-circle"></i>
          </span>
          @endif
          <a href="{{ route('requests.details', !$notice_note->issue ? [$notice_note->request_id, 'note' => $notice_note->id] : [$notice_note->request_id, 'note' => $notice_note->id, 'issue' => true]) }}">
            <img src="{{ asset('img/profile-img.png') }}" alt="" class="rounded-circle" />
            <div>
              <h4>{{ $notice_note->sender->name }}</h4>
              <p>{{ substr($notice_note->note, 0, 100) }}</p>
              <p>{{ $notice_note->created_at_local }}</p>
            </div>
          </a>
        </li>
        @if($i < count($notice_notes) - 1) <li>
          <hr class="dropdown-divider" />
    </li>
    @endif
    @endforeach
    </ul>
    @endif
    </li>
    <li class="nav-item dropdown pe-3">
      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="{{ asset('img/profile-img.png') }}" alt="Profile" class="rounded-circle" />
        <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name }}</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6>{{ $user->name }}</h6>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.index') }}">
            <i class="bi bi-person"></i>
            <span>My Profile</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ route('faq.index') }}">
            <i class="bi bi-question-circle"></i>
            <span>Need Help?</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sign Out</span>
          </a>
        </li>
      </ul>
    </li>
    </ul>
  </nav>
</header>
@endsection