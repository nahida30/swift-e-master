<div class="pagetitle">
  <div class="d-flex align-items-center justify-content-between">
    <div>
      <h1>{{ $pagename }}</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
          <li class="breadcrumb-item active">{{ $pagename }}</li>
        </ol>
      </nav>
    </div>
    @if(isset($button))
    {!! $button !!}
    @endif
  </div>
</div>