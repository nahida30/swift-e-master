@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', ($member && $member->id ? 'Edit' : 'Add') . ' User')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => ($member && $member->id ? 'Edit' : 'Add') . ' User'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@if($member && $member->id) Edit @else Add @endif User</h5>
            <form action="{{ route('users.submit') }}" method="POST" class="needs-validation {{ count($errors) ? 'was-validated' : '' }}" novalidate>
              @csrf
              <div class="row mb-3">
                <label for="first_name" class="col-sm-2 col-form-label">First Name<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', ($member && $member->first_name ? $member->first_name : '')) }}" required />
                  @if ($errors->has('first_name'))
                  <div class="invalid-feedback d-block">{{ $errors->first('first_name') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter first name</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="last_name" class="col-sm-2 col-form-label">Last Name<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', ($member && $member->last_name ? $member->last_name : '')) }}" required />
                  @if ($errors->has('last_name'))
                  <div class="invalid-feedback d-block">{{ $errors->first('last_name') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter last name</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email Address<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email', ($member && $member->email ? $member->email : '')) }}" required />
                  @if ($errors->has('email'))
                  <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter email address</div>
                  @endif
                </div>
              </div>
              @if(!$member)
              <div class="row mb-3">
                <label for="password" class="col-sm-2 col-form-label">Password<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password" name="password" required />
                  @if ($errors->has('password'))
                  <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter a new password</div>
                  @endif
                </div>
              </div>
              @endif
              <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Phone Number<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', ($member && $member->phone ? $member->phone : '')) }}" required />
                  @if ($errors->has('phone'))
                  <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter phone number</div>
                  @endif
                </div>
              </div>
              <div class="row mt-5">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  @if($member && $member->id)
                  <input type="hidden" name="id" value="{{ $member->id }}" />
                  @endif
                  <button type="submit" class="btn btn-primary">
                    @if($member && $member->id) Update @else Submit @endif
                  </button>
                  <a onclick="history.back()" class="btn btn-secondary">Cancel</a>
                  @if (session('message'))<small class="text-success ms-3 fw-light">{!! session('message') !!}</small>@endif
                </div>
              </div>
            </form>
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