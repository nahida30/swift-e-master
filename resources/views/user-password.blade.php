@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Change Password')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Change Password'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Change Password</h5>
            <form action="{{ route('users.password.save', $member->id) }}" method="POST" class="needs-validation {{ count($errors) ? 'was-validated' : '' }}" novalidate>
              @csrf
              <div class="row mb-3">
                <label for="new_password" class="col-sm-2 col-form-label">New Password<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="new_password" name="new_password" required />
                  @if ($errors->has('new_password'))
                    <div class="invalid-feedback d-block">{{ $errors->first('new_password') }}</div>
                  @else
                    <div class="invalid-feedback">Please enter a new password</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="new_password_confirmation" class="col-sm-2 col-form-label">Confirm Password<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required />
                  @if ($errors->has('new_password_confirmation'))
                    <div class="invalid-feedback d-block">{{ $errors->first('new_password_confirmation') }}</div>
                  @else
                    <div class="invalid-feedback">Please re-enter the new password</div>
                  @endif
                </div>
              </div>
              <div class="row mt-5">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <button type="submit" class="btn btn-primary">Change Password</button>
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