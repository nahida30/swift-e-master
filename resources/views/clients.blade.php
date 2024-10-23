@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Clients')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Clients'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">
              <div class="d-flex align-items-center">Clients</div>
              @if (session('message'))<small class="text-success fw-light">{!! session('message') !!}</small>@endif
              <div></div>
            </h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th class="text-center" width="150">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($clients as $client)
                  <tr>
                    <td>{{ $client->id }}</td>
                    <td><a href="{{ route('clients.details', $client->id) }}">{{ $client->company_name }}</a></td>
                    <td class="text-center">
                      <a class="btn btn-info btn-sm" href="{{ route('clients.add', $client->id) }}"><i class="bi bi-pencil-square"></i></a>
                      <a class="btn btn-warning btn-sm" href="{{ route('clients.password', $client->id) }}"><i class="bi bi-lock"></i></a>
                      <form action="{{ route('clients.delete', $client->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to DELETE this Client? This action can not be undone if performed.');"><i class="bi bi-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
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