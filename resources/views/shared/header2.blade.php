<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">
    <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto">
      <img src="{{ asset('img/logo192.png') }}" alt="logo">
      <div class="d-none d-lg-block">
        <h1 class="sitename">{{ config('app.name') }}</h1>
        <small>TIME IS MONEY. SO SAVE IT</small>
      </div>
    </a>
    <nav class="navmenu">
      <ul>
        <li><a href="{{ route('home') }}" class="active">Home<br></a></li>
        <li><a href="{{ route('home') }}#about">About Us</a></li>
        <li><a href="{{ route('home') }}#contact">Contact Us</a></li>
        <li><a href="{{ route('login') }}">Login</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
    <a class="btn-getstarted flex-md-shrink-0" href="{{ route('signup') }}">Get Started</a>
  </div>
</header>