@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', ($client && $client->id ? 'Edit' : 'Add') . ' Client')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => ($client && $client->id ? 'Edit' : 'Add') . ' Client'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@if($client && $client->id) Edit @else Add @endif Client</h5>
            <form action="{{ route('clients.submit') }}" method="POST" class="needs-validation {{ count($errors) ? 'was-validated' : '' }}" novalidate>
              @csrf
              <div class="row mb-3">
                <label for="first_name" class="col-sm-2 col-form-label">First Name<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', ($client && $client->first_name ? $client->first_name : '')) }}" required />
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
                  <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', ($client && $client->last_name ? $client->last_name : '')) }}" required />
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
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email', ($client && $client->email ? $client->email : '')) }}" required />
                  @if ($errors->has('email'))
                  <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter email address</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Phone Number<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', ($client && $client->phone ? $client->phone : '')) }}" required />
                  @if ($errors->has('phone'))
                  <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter phone number</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="client_type" class="col-sm-2 col-form-label">Membership<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <select name="client_type" id="client_type" class="form-select" required>
                    <option value="">Select Membership</option>
                    <option value="Gold Member" {{ 'Gold Member' == old('client_type', ($client && $client->client_type ? $client->client_type : '')) ? 'selected' : '' }}>Gold Member</option>
                    <option value="Pay as you go" {{ 'Pay as you go' == old('client_type', ($client && $client->client_type ? $client->client_type : '')) ? 'selected' : '' }}>Pay as you go</option>
                  </select>
                  @if ($errors->has('client_type'))
                  <div class="invalid-feedback d-block">{{ $errors->first('client_type') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter a membership</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="company_name" class="col-sm-2 col-form-label">Company Name<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', ($client && $client->company_name ? $client->company_name : '')) }}" required />
                  @if ($errors->has('company_name'))
                  <div class="invalid-feedback d-block">{{ $errors->first('company_name') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter company name</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="street_address" class="col-sm-2 col-form-label">Street Address<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="street_address" name="street_address" value="{{ old('street_address', ($client && $client->street_address ? $client->street_address : '')) }}" required />
                  @if ($errors->has('street_address'))
                  <div class="invalid-feedback d-block">{{ $errors->first('street_address') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter street address</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="city" class="col-sm-2 col-form-label">City<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="city" name="city" value="{{ old('city', ($client && $client->city ? $client->city : '')) }}" required />
                  @if ($errors->has('city'))
                  <div class="invalid-feedback d-block">{{ $errors->first('city') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter city</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="state" class="col-sm-2 col-form-label">State<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="state" name="state" value="{{ old('state', ($client && $client->state ? $client->state : '')) }}" required />
                  @if ($errors->has('state'))
                  <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter state</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="zip_code" class="col-sm-2 col-form-label">Zip Code<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', ($client && $client->zip_code ? $client->zip_code : '')) }}" required />
                  @if ($errors->has('zip_code'))
                  <div class="invalid-feedback d-block">{{ $errors->first('zip_code') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter zip code</div>
                  @endif
                </div>
              </div>
              <div class="row mt-5">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  @if($client && $client->id)
                  <input type="hidden" name="id" value="{{ $client->id }}" />
                  @endif
                  <button type="submit" class="btn btn-primary">
                    @if($client && $client->id) Update @else Submit @endif
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