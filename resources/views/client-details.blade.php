@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', $client->name)

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => $client->name])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row py-3">
              <div class="col-12 col-md-6">
                @include('shared.clients-menu')
              </div>
              <div class="col-12 col-md-6">
                @if (session('message'))<small class="text-success fw-light">{!! session('message') !!}</small>@endif
              </div>
            </div>
            <div class="profile">
              <div class="profile-overview">
                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">First Name</div>
                  <div class="col-lg-9 col-md-8">{{ $client->first_name }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Last Name</div>
                  <div class="col-lg-9 col-md-8">{{ $client->last_name }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email Address</div>
                  <div class="col-lg-9 col-md-8">{{ $client->email }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Phone Number</div>
                  <div class="col-lg-9 col-md-8">{{ $client->phone ?? '-'}}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Company Name</div>
                  <div class="col-lg-9 col-md-8">{{ $client->company_name ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Street Address</div>
                  <div class="col-lg-9 col-md-8">{{ $client->street_address ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">City</div>
                  <div class="col-lg-9 col-md-8">{{ $client->city ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">State</div>
                  <div class="col-lg-9 col-md-8">{{ $client->state ?? '-' }}</div>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Zip Code</div>
                  <div class="col-lg-9 col-md-8">{{ $client->zip_code ?? '-' }}</div>
                </div>
                @if($client->role == 'user')
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Membership</div>
                  <div class="col-lg-9 col-md-8">{{ $client->client_type ?? '-' }}</div>
                </div>
                @endif
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