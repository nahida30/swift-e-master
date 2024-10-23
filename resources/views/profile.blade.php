@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Profile')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Profile'])

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
        <div class="card">
          <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <img src="{{ asset('img/profile-img.png') }}" alt="Profile" class="rounded-circle" />
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
          </div>
        </div>
      </div>

      <div class="col-xl-8">
        <div class="card">
          <div class="card-body pt-3">
            <ul class="nav nav-tabs nav-tabs-bordered">
              <li class="nav-item">
                <button class="nav-link {{ !in_array(session('update'), ['profile', 'password']) ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
              </li>
              <li class="nav-item">
                <button class="nav-link {{ in_array(session('update'), ['profile']) ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>
              <li class="nav-item">
                <button class="nav-link {{ in_array(session('update'), ['password']) ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>
              @if (session('message'))
              <li class="nav-item d-flex flex-fill align-items-center justify-content-end"><small class="text-success fw-light">{!! session('message') !!}</small></li>
              @endif
            </ul>

            <div class="tab-content pt-2">
              <div class="tab-pane fade {{ !in_array(session('update'), ['profile', 'password']) ? 'show active' : '' }} profile-overview" id="profile-overview">
                <h5 class="card-title">Profile Details</h5>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">First Name</div>
                  <div class="col-lg-9 col-md-8">{{ $user->first_name }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Last Name</div>
                  <div class="col-lg-9 col-md-8">{{ $user->last_name }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email Address</div>
                  <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Phone Number</div>
                  <div class="col-lg-9 col-md-8">{{ $user->phone ?? '-'}}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Company Name</div>
                  <div class="col-lg-9 col-md-8">{{ $user->company_name ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Street Address</div>
                  <div class="col-lg-9 col-md-8">{{ $user->street_address ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">City</div>
                  <div class="col-lg-9 col-md-8">{{ $user->city ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">State</div>
                  <div class="col-lg-9 col-md-8">{{ $user->state ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Zip Code</div>
                  <div class="col-lg-9 col-md-8">{{ $user->zip_code ?? '-' }}</div>
                </div>
                @if($user->role == 'user')
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Membership</div>
                  <div class="col-lg-9 col-md-8">
                    {{ $user->client_type ?? '-' }}
                    @if($user->client_type == 'Pay as you go')
                    &nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="#"><small>Upgrade to Gold Member</small></a>
                    @endif
                    @if($user->client_type == 'Gold Member')
                    &nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="#"><small>Downgrade to Pay as you go</small></a>
                    @endif
                    &nbsp;&nbsp;<a class="btn btn-danger btn-sm" href="#"><small>Cancel Membership</small></a>
                  </div>
                </div>
                @endif
              </div>

              <div class="tab-pane fade {{ in_array(session('update'), ['profile']) ? 'show active' : '' }} profile-edit pt-3" id="profile-edit">
                <form action="{{ route('profile.update') }}" method="POST" class="needs-validation" novalidate>
                  @csrf
                  <div class="row mb-3">
                    <label for="first_name" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="first_name" type="text" class="form-control" id="first_name" value="{{ $user->first_name }}" required />
                      @if ($errors->has('first_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('first_name') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your first name</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="last_name" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="last_name" type="text" class="form-control" id="last_name" value="{{ $user->last_name }}" required />
                      @if ($errors->has('last_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('last_name') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your last name</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email Address</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="email" class="form-control" id="email" value="{{ $user->email }}" required />
                      @if ($errors->has('email'))
                      <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your email</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone Number</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="phone" type="text" class="form-control" id="phone" value="{{ $user->phone ?? ''}}" required />
                      @if ($errors->has('phone'))
                      <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your phone number</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="company_name" class="col-md-4 col-lg-3 col-form-label">Company Name</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="company_name" type="text" class="form-control" id="company_name" value="{{ $user->company_name ?? '' }}" required />
                      @if ($errors->has('company_name'))
                      <div class="invalid-feedback d-block">{{ $errors->first('company_name') }}</div>
                      @else
                      <div class="invalid-feedback">Enter company name</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="street_address" class="col-md-4 col-lg-3 col-form-label">Street Address</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" name="street_address" class="form-control" id="street_address" value="{{ $user->street_address ?? '' }}" required />
                      @if ($errors->has('street_address'))
                      <div class="invalid-feedback d-block">{{ $errors->first('street_address') }}</div>
                      @else
                      <div class="invalid-feedback">Enter street address</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="city" class="col-md-4 col-lg-3 col-form-label">City</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" name="city" class="form-control" id="city" value="{{ $user->city ?? '' }}" required />
                      @if ($errors->has('city'))
                      <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                      @else
                      <div class="invalid-feedback">Enter city</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="state" class="col-md-4 col-lg-3 col-form-label">State</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" name="state" class="form-control" id="state" value="{{ $user->state ?? '' }}" required />
                      @if ($errors->has('state'))
                      <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                      @else
                      <div class="invalid-feedback">Enter state</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="zip_code" class="col-md-4 col-lg-3 col-form-label">Zip Code</label>
                    <div class="col-md-8 col-lg-9">
                      <input type="text" name="zip_code" class="form-control" id="zip_code" value="{{ $user->zip_code ?? '' }}" required />
                      @if ($errors->has('zip_code'))
                      <div class="invalid-feedback d-block">{{ $errors->first('zip_code') }}</div>
                      @else
                      <div class="invalid-feedback">Enter zip code</div>
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-md-4 col-lg-3 col-form-label">&nbsp;</label>
                    <div class="col-md-8 col-lg-9">
                      <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade {{ in_array(session('update'), ['password']) ? 'show active' : '' }} pt-3" id="profile-change-password">
                <form action="{{ route('password.change') }}" method="POST" class="needs-validation" novalidate>
                  @csrf
                  <div class="row mb-3">
                    <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="current_password" type="password" class="form-control" id="current_password" required />
                      @if ($errors->has('current_password'))
                      <div class="invalid-feedback d-block">{{ $errors->first('current_password') }}</div>
                      @else
                      <div class="invalid-feedback">Enter your current password</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="new_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="new_password" type="password" class="form-control" id="new_password" required />
                      @if ($errors->has('new_password'))
                      <div class="invalid-feedback d-block">{{ $errors->first('new_password') }}</div>
                      @else
                      <div class="invalid-feedback">Enter a new password</div>
                      @endif
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation" required />
                      @if ($errors->has('new_password_confirmation'))
                      <div class="invalid-feedback d-block">{{ $errors->first('new_password_confirmation') }}</div>
                      @else
                      <div class="invalid-feedback">Please re-enter the new password</div>
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <label class="col-md-4 col-lg-3 col-form-label">&nbsp;</label>
                    <div class="col-md-8 col-lg-9">
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
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