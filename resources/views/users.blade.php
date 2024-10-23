@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Users')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Users'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">
              <div class="d-flex align-items-center">Users</div>
              @if (session('message'))<small class="text-success fw-light">{!! session('message') !!}</small>@endif
              @if($user->isAdmin())
              <a class="btn btn-primary" href="{{ route('users.add', 0) }}"><i class="bi bi-plus-lg"></i> Add</a>
              @else
              <div></div>
              @endif
            </h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($members as $member)
                  <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone }}</td>
                    <td class="text-center">
                      <a class="btn btn-info btn-sm" href="{{ route('users.add', $member->id) }}"><i class="bi bi-pencil-square"></i></a>
                      <a class="btn btn-warning btn-sm" href="{{ route('users.password', $member->id) }}"><i class="bi bi-lock"></i></a>
                      <form action="{{ route('users.delete', $member->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to DELETE this User? This action can not be undone if performed.');"><i class="bi bi-trash"></i></button>
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