@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Pricing')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Pricing'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0"></h5>
            <p>PAY AS YOU GO SERVICE FEE <b>$10.95</b> PER TRANSACTION</p>
            <p>GOLD MEMBER SERVICE FEE <b>$7.95</b> PER TRANSACTION AND <b>$96.00</b> ANUALLY.</p>
            <p>PALM BEACH COUNTY RECORDING FEES</p>
            <p>Recording any instrument not more than <b>8½" x 14"</b>, first page <b>$10.00</b></p>
            <p>Recording any instrument not more than <b>8½" x 14"</b>, Each additional page or fraction <b>$8.50</b></p>
            <p>Certifying copies, per instrument <b>$10.00</b></p>
            <p>Lis Pendens FIRST PAGE <b>$5.00</b></p>
            <p>Lis Pendens EACH ADDITIONAL PAGE <b>$4.00</b></p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection


@push('scripts')
<script>

</script>
@endpush