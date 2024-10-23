@extends('shared.layout')

@section('title', 'Login')

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
                  <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  <p class="text-center small">Enter your email address & password to login</p>
                </div>
                <form action="{{ route('login.submit') }}" method="POST" class="row g-3 needs-validation" novalidate>
                  @csrf
                  <div class="col-12">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                      <span class="input-group-text">@</span>
                      <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required />
                      @if ($errors->has('email'))
                      <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                      @else
                      <div class="invalid-feedback">Please enter your email address</div>
                      @endif
                    </div>
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required />
                    @if ($errors->has('password'))
                    <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
                    @else
                    <div class="invalid-feedback">Please enter your password</div>
                    @endif
                  </div>
                  <div class="col-12">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="rememberme" id="rememberme" />
                      <label class="form-check-label" for="rememberme">Remember me</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <input type="hidden" name="timezone_offset" id="timezone_offset" />
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                  </div>
                  <div class="col-12">
                    <p class="small mb-0 text-center">Don't have account? <a href="{{ route('signup') }}">Create an account</a></p>
                  </div>
                </form>
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
  $(document).ready(function() {
    $('#timezone_offset').val((new Date()).getTimezoneOffset() * 60)
  })
</script>
@endpush