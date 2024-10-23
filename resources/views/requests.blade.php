@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Requests')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Requests'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0" style="height:60px">
              <div class="d-flex align-items-center">Requests</div>
              @if (session('message'))<small class="text-success fw-light">{!! session('message') !!}</small>@endif
              @if(!$user->isAdmin())
              <a class="btn btn-primary" href="{{ route('requests.add', 0) }}"><i class="bi bi-plus-lg"></i> Add</a>
              @else
              <button type="button" class="btn btn-primary btn-sm" style="display:none" id="create-invoice-btn">
                <i class="bi bi-plus-lg"></i> Create Invoice
              </button>
              @endif
            </h5>
            <div class="table-responsive">
              <table class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    @if($user->isAdmin())
                    <th class="text-center">Select</th>
                    <th>Username</th>
                    @endif
                    <th>Tag It</th>
                    <th>Doc Type</th>
                    <th>Uploaded</th>
                    <th>Submitted</th>
                    <th>State</th>
                    <th>County</th>
                    <th>Status</th>
                    <th class="text-center" width="240">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($requests as $request)
                  <tr>
                    @if($user->isAdmin())
                    <th class="text-center">
                      @if($request->status=='In-Process')
                      @if(!isset($request->invoice->id))
                      <input type="checkbox" class="user-checkbox" data-userid="{{ $request->user_id }}" value="{{ $request->id }}" />
                      @endif
                      @endif
                    </th>
                    <td>{{ $request->user->name }}</td>
                    @endif
                    <td><a href="{{ route('requests.details', $request->id) }}">{{ $request->tag_it }} #{{ $request->count }}</a></td>
                    <td>{{ $requestTypes[$request->doc_type] }}</td>
                    <td>{{ $request->uploaded_at_local }}</td>
                    <td>{{ $request->created_at_local }}</td>
                    <td>{{ $request->state }}</td>
                    <td>{{ $request->county }}</td>
                    <td>{{ $request->status }}</td>
                    <td class="text-center">
                      @if($user->isAdmin())
                      <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details" href="{{ route('requests.details', $request->id) }}">
                        <i class="bi bi-receipt"></i>
                      </a>
                      <!-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Swift-E Notes" href="{{ route('requests.notes', $request->id) }}">
                        <i class="bi bi-journal-text"></i>
                      </a> -->
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewFile('{{ $request->file }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('requests.add', $request->id) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      @if($request->status == 'Sent')
                      <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Accept" href="{{ route('requests.accept', $request->id) }}" onclick="return confirm('Are you sure you want to ACCEPT this Request?');">
                        <i class="bi bi-check"></i>
                      </a>
                      @endif
                      <form action="{{ route('requests.delete', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel" onclick="return confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.');">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                      @else
                      @if($request->completed_file)
                      <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download" href="{{ $request->completed_file }}" target="_blank">
                        <i class="bi bi-download"></i>
                      </a>
                      @endif
                      <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details" href="{{ route('requests.details', $request->id) }}">
                        <i class="bi bi-receipt"></i>
                      </a>
                      <!-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Swift-E Notes" href="{{ route('requests.notes', $request->id) }}">
                        <i class="bi bi-journal-text"></i>
                      </a> -->
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewFile('{{ $request->file }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      @if($request->status == 'Sent')
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('requests.add', $request->id) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('requests.delete', $request->id) }}" method="POST" class="d-inline">
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
  var firstUserSelected = null

  $(document).on('change', '.user-checkbox', function() {
    var userId = $(this).data('userid')

    if (firstUserSelected === null && $(this).is(':checked')) {
      firstUserSelected = userId

      $('.user-checkbox').each(function() {
        if ($(this).data('userid') !== firstUserSelected) {
          $(this).prop('disabled', true)
        }
      })
    }

    if (userId === firstUserSelected) {
      if ($('.user-checkbox[data-userid="' + firstUserSelected + '"]:checked').length > 0) {
        $('#create-invoice-btn').show()
      } else {
        $('#create-invoice-btn').hide()
      }
    } else {
      $(this).prop('checked', false).prop('disabled', true)
    }

    if ($('.user-checkbox:checked').length === 0) {
      firstUserSelected = null
      $('.user-checkbox').prop('disabled', false)
      $('#create-invoice-btn').hide()
    }
  })

  updateButtonVisibility()

  function updateButtonVisibility() {
    if (firstUserSelected !== null && $('.user-checkbox[data-userid="' + firstUserSelected + '"]:checked').length > 0) {
      $('#create-invoice-btn').show()
    } else {
      $('#create-invoice-btn').hide()
    }
  }

  $(document).on('click', '#create-invoice-btn', function() {
    let requestIds = $('.user-checkbox:checked').map(function() {
      return $(this).val()
    }).get()
    window.location.href = `{{ route('invoices.add', 0) }}/?requests=${requestIds.join()}`
  })
</script>
@endpush