@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Helpful Videos')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Helpful Videos'])

  <section class="section">
    <div class="row">
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">New Requests</h5>
            <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox">
              <img src="{{ asset('img/hero-img.png') }}" class="img-fluid animated" alt="" />
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">Swift-E Notes</h5>
            <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox">
              <img src="{{ asset('img/hero-img.png') }}" class="img-fluid animated" alt="" />
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">Invoice Payments</h5>
            <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox">
              <img src="{{ asset('img/hero-img.png') }}" class="img-fluid animated" alt="" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection


@push('scripts')
<script>
  $(document).ready(function() {
    const glightbox = GLightbox({
      selector: '.glightbox'
    })
  })
</script>
@endpush