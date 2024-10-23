<nav class="nav nav-pills nav-fill">
  @php
  $curRoute = Route::currentRouteName();
  @endphp
  <a class="nav-link {{ $curRoute == 'clients.details' ? 'active' : '' }}" aria-current="page" href="{{ route('clients.details', $client->id) }}">Details</a>
  <a class="nav-link {{ $curRoute == 'clients.requests' ? 'active' : '' }}" aria-current="page" href="{{ route('clients.requests', $client->id) }}">Requests</a>
  <a class="nav-link {{ $curRoute == 'clients.invoices' ? 'active' : '' }}" aria-current="page" href="{{ route('clients.invoices', $client->id) }}">Invoices</a>
</nav>