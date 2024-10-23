@extends('shared.layout')

@section('title', 'Signup')

@push('styles')
<style>
  .phone-inputs {
    gap: 5px;
    display: flex;
  }

  .dash {
    padding: 5px 0;
  }

  .part1,
  .part2 {
    flex: 30%;
  }

  .part3 {
    flex: 40%;
  }
</style>
@endpush

@section('content')
<main>
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 col-md-6 d-flex flex-column align-items-center justify-content-center">
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
                  <h5 class="card-title text-center pb-0 fs-4">
                    @if(session('signup_step') == 'otp')
                    Verify Email Address
                    @elseif(session('signup_step') == 'membership')
                    Membership Packages
                    @else
                    Create an Account
                    @endif
                  </h5>
                  <p class="text-center small">
                    @if(session('signup_step') == 'otp')
                    A 6 digit code has been sent to your email address.<br />Please check now and enter the code below
                    @elseif(session('signup_step') == 'membership')
                    Choose a membership package to complete your account
                    @else
                    Enter your personal details to create account
                    @endif
                  </p>
                </div>

                @if (session('message'))
                <p class="text-center text-success mx-3">{!! session('message') !!}</p>
                @endif

                <form action="{{ route('signup.submit') }}" method="POST" class="needs-validation {{ count($errors) ? 'was-validated' : '' }}" novalidate>
                  @csrf

                  <div class="row g-3 mb-3 {{ !session('signup_step') ? '' : 'd-none' }}">
                    <div class="col-12 col-md-6">
                      <label for="first_name" class="form-label">First Name<small class="text-danger">*</small>:</label>
                      <input type="text" name="first_name" class="form-control" id="first_name" value="{{ session('user.first_name', old('first_name')) }}" required />
                      @if ($errors->has('first_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('first_name') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your first name</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="last_name" class="form-label">Last Name<small class="text-danger">*</small>:</label>
                      <input type="text" name="last_name" class="form-control" id="last_name" value="{{ session('user.last_name', old('last_name')) }}" required />
                      @if ($errors->has('last_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('last_name') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your last name</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="email" class="form-label">Email Address<small class="text-danger">*</small>:</label>
                      <input type="email" name="email" class="form-control" id="email" value="{{ session('user.email', old('email')) }}" required />
                      @if ($errors->has('email'))
                      <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your email address</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="password" class="form-label">Password<small class="text-danger">*</small>: <a data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Password should be minimum 8 characters long"><i class="bi bi-info-circle"></i></a></label>
                      <input type="password" name="password" class="form-control" id="password" value="{{ session('user.password') }}" required />
                      @if ($errors->has('password'))
                      <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
                      @else
                      <div class="invalid-feedback">Enter a password</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="phone" class="form-label">Phone Number<small class="text-danger">*</small>:</label>
                      <input type="text" name="phone" class="form-control" id="phone" pattern="\d{3}-\d{3}-\d{4}" value="{{ session('user.phone', old('phone')) }}" required />
                      @if ($errors->has('phone'))
                      <div id="phone-error" class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                      @else
                      <div id="phone-error" class="invalid-feedback">Enter your phone number</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="company_name" class="form-label">Company Name<small class="text-danger">*</small>:</label>
                      <input type="text" name="company_name" class="form-control" id="company_name" value="{{ session('user.company_name', old('company_name')) }}" required />
                      @if ($errors->has('company_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('company_name') }}</div>
                      @else
                      <div class="invalid-feedback">Enter company name</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="street_address" class="form-label">Street Address<small class="text-danger">*</small>:</label>
                      <input type="text" name="street_address" class="form-control" id="street_address" value="{{ session('user.street_address', old('street_address')) }}" required />
                      @if ($errors->has('street_address'))
                      <div class="invalid-feedback d-block">{{ $errors->first('street_address') }}</div>
                      @else
                      <div class="invalid-feedback">Enter street address</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="city" class="form-label">City<small class="text-danger">*</small>:</label>
                      <input type="text" name="city" class="form-control" id="city" value="{{ session('user.city', old('city')) }}" required />
                      @if ($errors->has('city'))
                      <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                      @else
                      <div class="invalid-feedback">Enter city</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="state" class="form-label">State<small class="text-danger">*</small>:</label>
                      <input type="text" name="state" class="form-control" id="state" value="{{ session('user.state', old('state')) }}" required />
                      @if ($errors->has('state'))
                      <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                      @else
                      <div class="invalid-feedback">Enter state</div>
                      @endif
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="zip_code" class="form-label">Zip Code<small class="text-danger">*</small>:</label>
                      <input type="text" name="zip_code" class="form-control" id="zip_code" value="{{ session('user.zip_code', old('zip_code')) }}" required />
                      @if ($errors->has('zip_code'))
                      <div class="invalid-feedback d-block">{{ $errors->first('zip_code') }}</div>
                      @else
                      <div class="invalid-feedback">Enter zip code</div>
                      @endif
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" id="terms" checked="{{ session('user.terms', old('terms')) == true }}" required />
                        <label class="form-check-label" for="terms">I agree and accept the <a href="#">terms and conditions</a></label>
                        @if ($errors->has('terms'))
                        <div class="invalid-feedback d-block">{{ $errors->first('terms') }}</div>
                        @else
                        <div class="invalid-feedback">Agree the terms before submitting</div>
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="row g-3 mb-3 {{ session('signup_step') == 'otp' ? '' : 'd-none' }}">
                    <div class="col-12">
                      <label for="otp" class="form-label">Enter 6 Digit Code<small class="text-danger">*</small>:</label>
                      <input type="text" name="otp" class="form-control" id="otp" value="{{ session('otp_entered') }}" {{ session('signup_step') == 'otp' ? 'required' : '' }} />
                      @if ($errors->has('otp'))
                      <div class="invalid-feedback d-block">{{ $errors->first('otp') }}</div>
                      @else
                      <div class="invalid-feedback">Enter 6 digit code</div>
                      @endif
                    </div>
                  </div>

                  <div class="row g-3 mb-3 {{ session('signup_step') == 'membership' ? '' : 'd-none' }}">
                    <div class="col-12">
                      <input type="radio" class="btn-check" name="client_type" id="gold-member" value="Gold Member" autocomplete="off" {{ session('signup_step') == 'membership' ? 'required' : '' }} />
                      <label class="btn btn-outline-secondary w-100" for="gold-member">
                        <p><strong>GOLD MEMBER SERVICE FEE $7.95 PER TRANSACTION AND $96.00 ANUALLY.</strong></p>
                        <p class="small">Sign up for our Gold Member package today and save on each transaction. Pay only $96 for the year and just $7.95 per transaction</p>
                      </label>
                    </div>
                    <div class="col-12">
                      <input type="radio" class="btn-check" name="client_type" id="pay-as-you-go" value="Pay as you go" autocomplete="off" />
                      <label class="btn btn-outline-secondary w-100" for="pay-as-you-go">
                        <p><strong>PAY AS YOU GO SERVICE FEE $10.95 PER TRANSACTION</strong></p>
                        <p class="small">For pay as you go members, each transaction costs only $10.95.</p>
                      </label>
                      @if ($errors->has('client_type'))
                      <div class="invalid-feedback d-block">{{ $errors->first('client_type') }}</div>
                      @else
                      <div class="invalid-feedback">Choose a membership package</div>
                      @endif
                    </div>
                  </div>

                  <div class="col-12 mt-4">
                    <input type="hidden" name="timezone_offset" id="timezone_offset" />
                    <button class="btn btn-primary w-100" type="submit">
                      @if(session('signup_step') == 'otp')
                      Verify & Continue
                      @elseif(session('signup_step') == 'membership')
                      <span id="signup-btn-text">Create Account</span>
                      @else
                      Continue
                      @endif
                    </button>
                  </div>

                  <div class="col-12">
                    <p class="small mb-0 mt-3 text-center">
                      @if(session('signup_step') == 'otp')
                      <a href="{{ route('home') }}">Cancel</a>
                      @elseif(session('signup_step') == 'membership')
                      <a href="{{ route('home') }}">Cancel</a>
                      @else
                      Already have an account? <a href="{{ route('login') }}">Log in</a>
                      @endif
                    </p>
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
  const phoneInput = document.getElementById('phone')
  phoneInput.addEventListener('input', (event) => {
    let value = event.target.value.replace(/\D/g, '')
    if (value.length > 3 && value.length <= 6) {
      value = `${value.slice(0, 3)}-${value.slice(3)}`
    } else if (value.length > 6) {
      value = `${value.slice(0, 3)}-${value.slice(3, 6)}-${value.slice(6, 10)}`
    }

    event.target.value = value
  })

  phoneInput.addEventListener('blur', (event) => {
    const pattern = /^\d{3}-\d{3}-\d{4}$/
    if (!pattern.test(event.target.value)) {
      $('#phone-error').text('Enter a valid phone number')
    } else {
      $('#phone-error').text('')
    }
  })

  $(document).ready(function() {
    $('#timezone_offset').val((new Date()).getTimezoneOffset() * 60)

    $('#gold-member').on('click', function() {
      $('#signup-btn-text').text('Pay $96.00 to Create Account')
    })

    $('#pay-as-you-go').on('click', function() {
      $('#signup-btn-text').text('Create Account')
    })
  })
</script>
@endpush