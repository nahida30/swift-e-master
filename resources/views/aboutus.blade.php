@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'About Us')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'About Us'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0"></h5>
            <p>Time is Money and we intend to save you that money by cutting back the time it takes for you to record your documents.</p>
            <p>Your number 1 priority as a business is steady growth. And we know that if you are growing that means that you have found ways in your business to increase both productivity and efficiency. That is where we come in. By eliminating the need, costs, and liability of driving to a county recorders office, going through security check points, towing a line, and dealing with possible clerical errors when your finally served. We will streamline this part of your business so that you can worry most on what matters. We have been in the physical recording business for 18 years hence we can speak from experience and then can use our experience to provide you with an excellent product. Time is money and we intend to save you some.</p>
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