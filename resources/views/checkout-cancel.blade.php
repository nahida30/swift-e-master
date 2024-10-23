@extends('shared.layout')

@section('title', 'Checkout Cancel')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main>
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex flex-column justify-content-center align-items-center py-2">
              <a href="{{ route('home') }}" class="logo d-flex align-items-center mb-2 w-auto">
                <img src="{{ asset('img/logo192.png') }}" alt="logo" />
                <div class="d-none d-lg-block">
                  <span class="d-block">{{ config('app.name') }}</span>
                  <small>TIME IS MONEY. SO SAVE IT</small>
                </div>
              </a>
            </div>
            <div class="card w-100 mb-3">
              <div class="card-body">
                <div class="pt-4 pb-2">
                  @if($status == 'processing')
                  <h5 class="card-title text-center pb-0 fs-4 mb-4">Payment Processing</h5>
                  <p>Your payment is currently being processed. Please wait a moment.</p>
                  <p>Do not close or refresh the page.</p>
                  @endif

                  @if($status == 'succeeded' || $status == 'active')
                  <h5 class="card-title text-center pb-0 fs-4 mb-4">Payment Successful</h5>
                  <p>Thank you for your payment. Your transaction was successful!</p>
                  <p>Order details and confirmation will be sent to your email.</p>
                  @endif

                  @if($status == 'canceled')
                  <h5 class="card-title text-center pb-0 fs-4 mb-4">Payment Canceled</h5>
                  <p>Your payment process was canceled.</p>
                  <p>If you intentionally canceled the payment, you can return to the site to initiate a new payment.</p>
                  <p>If you encountered any issues, please contact customer support for assistance.</p>
                  @endif

                  @if($status == 'payment_failed')
                  <h5 class="card-title text-center pb-0 fs-4 mb-4">Payment Failed</h5>
                  <p>We're sorry, but your payment was not successful.</p>
                  <p>Please check your payment details and try again.</p>
                  <p>If the issue persists, contact customer support for assistance.</p>
                  @endif

                  @if($status == 'pending')
                  <h5 class="card-title text-center pb-0 fs-4 mb-4">Payment Pending</h5>
                  <p>Your payment is currently pending processing. This could be due to additional verification or other reasons.</p>
                  <p>Please wait for further updates. We appreciate your patience.</p>
                  @endif
                </div>

                <div class="row">
                  <div class="col-6">
                    <p class="small mb-0 text-center">
                      <a href="{{ route('home') }}">Back to Home</a>
                    </p>
                  </div>
                  <div class="col-6">
                    <p class="small mb-0 text-center">
                      <a href="{{ route('login') }}">Log in here</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
            @include('shared.designby')
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
@endsection


@push('scripts')
<script>

</script>
@endpush