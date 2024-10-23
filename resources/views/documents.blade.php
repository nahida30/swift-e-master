@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Documents')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Documents'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">
              <div class="d-flex align-items-center">Documents</div>
              @if (session('message'))<small class="text-success fw-light">{!! session('message') !!}</small>@endif
              <div></div>
            </h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    @if($user->isAdmin())<th>Username</th>@endif
                    <th>Tag It</th>
                    <th>Doc Type</th>
                    <th>Uploaded</th>
                    <th>Submitted</th>
                    <th>State</th>
                    <th>County</th>
                    <th>Status</th>
                    <th class="text-center" width="200">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($documents as $document)
                  <tr>
                    @if($user->isAdmin())<td>{{ $document->user->name }}</td>@endif
                    <td><a href="{{ route('requests.details', $document->id) }}">{{ $document->tag_it }} #{{ $document->count }}</a></td>
                    <td>{{ $requestTypes[$document->doc_type] }}</td>
                    <td>{{ $document->uploaded_at_local }}</td>
                    <td>{{ $document->created_at_local }}</td>
                    <td>{{ $document->state }}</td>
                    <td>{{ $document->county }}</td>
                    <td>{{ $document->status }}</td>
                    <td class="text-center">
                      @if($user->isAdmin())
                      <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details" href="{{ route('requests.details', $document->id) }}">
                        <i class="bi bi-receipt"></i>
                      </a>
                      <!-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Swift-E Notes" href="{{ route('requests.notes', $document->id) }}">
                        <i class="bi bi-journal-text"></i>
                      </a> -->
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewFile('{{ $document->file }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('requests.add', $document->id) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('requests.delete', $document->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel" onclick="return confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.');">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                      @else
                      @if($document->completed_file)
                      <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download" href="{{ $document->completed_file }}" target="_blank">
                        <i class="bi bi-download"></i>
                      </a>
                      @endif
                      <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details" href="{{ route('requests.details', $document->id) }}">
                        <i class="bi bi-receipt"></i>
                      </a>
                      <!-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Swift-E Notes" href="{{ route('requests.notes', $document->id) }}">
                        <i class="bi bi-journal-text"></i>
                      </a> -->
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewFile('{{ $document->file }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      @if($document->status == 'Sent')
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('requests.add', $document->id) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('requests.delete', $document->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel" onclick="return confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.');">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                      @endif
                      @endif
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

@include('includes.modals.pdf-view')

@endsection


@push('scripts')
<script>

</script>
@endpush