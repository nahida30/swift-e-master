@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', ($request && $request->id ? 'Edit' : 'Add') . ' Request')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => ($request && $request->id ? 'Edit' : 'Add') . ' Request'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">@if($request && $request->id) Edit @else Add @endif Request</h5>
            <form id="request-form" action="{{ route('requests.submit') }}" method="POST" class="needs-validation {{ count($errors) ? 'was-validated' : '' }}" novalidate>
              @csrf
              <div class="row mb-3">
                <label for="doc_type" class="col-sm-2 col-form-label">Document Type<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <select class="form-select" id="doc_type" name="doc_type" required>
                    <option value="">Select Document Type</option>
                    @foreach($requestTypes as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" {{ $optionValue == old('doc_type', ($request && $request->doc_type ? $request->doc_type : '')) ? 'selected' : '' }}>{{ $optionLabel }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('doc_type'))
                  <div class="invalid-feedback d-block">{{ $errors->first('doc_type') }}</div>
                  @else
                  <div class="invalid-feedback">Please choose document type</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="tag_it" class="col-sm-2 col-form-label">Tag It<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="tag_it" name="tag_it" value="{{ old('tag_it', ($request && $request->tag_it ? $request->tag_it : '')) }}" required />
                  @if ($errors->has('tag_it'))
                  <div class="invalid-feedback d-block">{{ $errors->first('tag_it') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter a tag</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="state" class="col-sm-2 col-form-label">State<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <select class="form-select" id="state" name="state" required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                    <option value="{{ $state->state }}" {{ $state->state == old('state', ($request && $request->state ? $request->state : '')) ? 'selected' : '' }}>{{ $state->state }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('state'))
                  <div class="invalid-feedback d-block">{{ $errors->first('state') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter your state</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="county" class="col-sm-2 col-form-label">County<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <select class="form-select" id="county" name="county" required>
                    <option value="">Select County</option>
                    @foreach($counties as $county)
                    <option value="{{ $county->name }}" {{ $county->name == old('county', ($request && $request->county ? $request->county : '')) ? 'selected' : '' }}>{{ $county->name }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('county'))
                  <div class="invalid-feedback d-block">{{ $errors->first('county') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter your county</div>
                  @endif
                </div>
              </div>
              <div class="row mb-3">
                <label for="file" class="col-sm-2 col-form-label">Upload File<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="hidden" id="fileurl" name="fileurl" value="{{ $request && $request->file ? $request->file : '' }}" />
                  <input type="hidden" id="file_name" name="file_name" value="{{ $request && $request->file_name ? $request->file_name : '' }}" />
                  <input type="hidden" id="uploaded_at" name="uploaded_at" value="{{ $request && $request->uploaded_at ? $request->uploaded_at : '' }}" />
                  <input type="hidden" id="original_name" name="original_name" value="{{ $request && $request->original_name ? $request->original_name : '' }}" />
                  <input type="hidden" id="tiff_pages" name="tiff_pages" value="{{ $request && $request->tiff_pages ? $request->tiff_pages : 0 }}" />
                  <div id="file-input">
                    @if(!$request)
                    <input type="file" class="form-control" id="file" name="file" accept="application/pdf" aria-describedby="fileHelpBlock" {{ $request && $request->file ? '' : 'required' }} />
                    <div id="fileHelpBlock" class="form-text">Allowed Filetype: PDF. Allowed Filesize: 24MB Max.</div>
                    @if ($errors->has('file'))
                    <div id="file-error" class="invalid-feedback d-block">{{ $errors->first('file') }}</div>
                    @else
                    <div id="file-error" class="invalid-feedback">Please upload your file</div>
                    @endif
                    @endif
                  </div>
                  <div id="file-data">
                    @if($request && $request->file)
                    <div class="upload-wrap">
                      <div class="me-4">{{ $request->uploaded_at_local }}</div>
                      <div class="me-3">
                        <p class="mb-0">
                          <i class="bi bi-file-earmark-pdf"></i>
                          <a style="cursor:pointer" onclick="onViewFile('{{ $request->file }}')">{{ $request->original_name }}</a>
                        </p>
                        <p class="text-danger mb-0 small">Click link above to review document</p>
                      </div>
                      <a class="text-center text-danger remove-file" id="remove-file" data-name="{{ $request->file_name }}">
                        <i class="bi bi-trash"></i><br /><small>Remove</small>
                      </a>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              @if($user->isAdmin())
              <div class="row mb-3">
                <label for="status" class="col-sm-2 col-form-label">Status<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <select class="form-select" id="status" name="status" required>
                    <option value="">Select Status</option>
                    <option value="Sent" {{ 'Sent' == old('status', ($request && $request->status ? $request->status : '')) ? 'selected' : '' }}>Sent</option>
                    <option value="In-Process" {{ 'In-Process' == old('status', ($request && $request->status ? $request->status : '')) ? 'selected' : '' }}>In-Process</option>
                    <option value="Complete" {{ 'Complete' == old('status', ($request && $request->status ? $request->status : '')) ? 'selected' : '' }}>Complete</option>
                    <option value="Issue" {{ 'Issue' == old('status', ($request && $request->status ? $request->status : '')) ? 'selected' : '' }}>Issue</option>
                  </select>
                  @if ($errors->has('status'))
                  <div class="invalid-feedback d-block">{{ $errors->first('status') }}</div>
                  @else
                  <div class="invalid-feedback">Please choose status</div>
                  @endif
                </div>
              </div>
              @if($request && $request->status == 'In-Process')
              <div class="row mb-3">
                <label for="completed_file" class="col-sm-2 col-form-label">Upload Completed File<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="hidden" id="completed_fileurl" name="completed_fileurl" value="{{ $request && $request->completed_file ? $request->completed_file : '' }}" />
                  <input type="hidden" id="completed_file_name" name="completed_file_name" value="{{ $request && $request->completed_file_name ? $request->completed_file_name : '' }}" />
                  <input type="hidden" id="completed_at" name="completed_at" value="{{ $request && $request->completed_at ? $request->completed_at : '' }}" />
                  <input type="hidden" id="completed_original_name" name="completed_original_name" value="{{ $request && $request->completed_original_name ? $request->completed_original_name : '' }}" />
                  <div id="completed-file-input">
                    @if($request && !$request->completed_file)
                    <input type="file" class="form-control" id="completed_file" name="completed_file" accept="application/pdf" aria-describedby="completedFileHelpBlock" {{ $request && $request->completed_file ? '' : 'required' }} />
                    <div id="completedFileHelpBlock" class="form-text">Allowed Filetype: PDF. Allowed Filesize: 24MB Max.</div>
                    @if ($errors->has('completed_file'))
                    <div id="completed-file-error" class="invalid-feedback d-block">{{ $errors->first('completed_file') }}</div>
                    @else
                    <div id="completed-file-error" class="invalid-feedback">Please upload completed file</div>
                    @endif
                    @endif
                  </div>
                  <div id="completed-file-data">
                    @if($request && $request->completed_file)
                    <div class="upload-wrap">
                      <div class="me-4">{{ $request->completed_at_local }}</div>
                      <div class="me-3">
                        <p class="mb-0">
                          <i class="bi bi-file-earmark-pdf"></i>
                          <a style="cursor:pointer" onclick="onViewFile('{{ $request->completed_file }}')">{{ $request->completed_original_name }}</a>
                        </p>
                        <p class="text-danger mb-0 small">Click link above to view document</p>
                      </div>
                      <a class="text-center text-danger remove-file" id="completed-remove-file" data-name="{{ $request->completed_file_name }}">
                        <i class="bi bi-trash"></i><br /><small>Remove</small>
                      </a>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              @endif
              @endif
              <div class="row mt-5">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  @if($request && $request->id)
                  <input type="hidden" name="id" value="{{ $request->id }}" />
                  @endif

                  @if(!$request)
                  <button type="submit" class="btn btn-primary">Save</button>
                  @endif

                  @if($request && $request->status == 'Sent' && !$user->isAdmin())
                  <button type="submit" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-danger" onclick="onCancelRequest()">Cancel Request</button>
                  @endif

                  @if($request && $user->isAdmin())
                  <button type="submit" class="btn btn-primary">Update</button>
                  @if($request->status == 'Sent')
                  <a class="btn btn-success" href="{{ route('requests.accept', $request->id) }}" onclick="return confirm('Are you sure you want to ACCEPT this Request?');">
                    Accept Request
                  </a>
                  @endif
                  <button type="button" class="btn btn-danger" onclick="onCancelRequest()">Cancel Request</button>
                  @endif

                  @if($request && $request->id)
                  <a class="btn btn-warning" onclick="onViewNote('{{ $request->id }}')">Swift-E Notes</a>
                  @endif

                  <a onclick="history.back()" class="btn btn-secondary">@if($request && $request->id) Back @else Cancel @endif</a>

                  @if (session('message'))<small class="text-success ms-3 fw-light">{!! session('message') !!}</small>@endif
                </div>
              </div>
            </form>
            @if(($request && $request->status == 'Sent' && !$user->isAdmin()) || ($request && $user->isAdmin()))
            <form action="{{ route('requests.delete', $request->id) }}" method="POST" id="cancel-form">
              @csrf
              @method('DELETE')
            </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

@include('includes.modals.pdf-view')
@include('includes.modals.note-view')

@endsection


@push('scripts')
<script>
  $(document).ready(function() {
    "@if($request)"
    $('#county option[value="{{ $request->county }}"]').attr("selected", "selected")
    "@endif"

    $(document).on('change', '#file', function() {
      const fileInput = this
      $('#file-error').empty()
      if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0]

        if (file.type != 'application/pdf') {
          $('#file-error').html('Only PDF files are allowed to upload').show()
          $('#file').val('')
          return
        }

        if (file.size > 24 * 1024 * 1024) {
          $('#file-error').html('The file size must not exceed 24MB').show()
          $('#file').val('')
          return
        }

        showLoader()
        let formData = new FormData()
        formData.append('file', file)
        $.ajax({
          type: 'POST',
          data: formData,
          url: '/uploadfile',
          contentType: false,
          processData: false,
          success: function(data) {
            $('#file-input').hide()
            $('#file-data').html(`<div class="upload-wrap">
              <div class="me-4">${data.uploaded_at_local}</div>
              <div class="me-3">
                <p class="mb-0">
                  <i class="bi bi-file-earmark-pdf"></i>
                  <a style="cursor:pointer" onclick="onViewFile('${data.file}')">${data.original_name}</a>
                </p>
                <p class="text-danger mb-0 small">Click link above to review document</p>
              </div>
              <a class="text-center text-danger remove-file" id="remove-file" data-name="${data.file_name}">
                <i class="bi bi-trash"></i><br /><small>Remove</small>
              </a>
            </div>`)
            $('#file').val('')
            $('#file').removeAttr('required')
            $('#fileurl').val(data.file)
            $('#file_name').val(data.file_name)
            $('#uploaded_at').val(data.uploaded_at)
            $('#original_name').val(data.original_name)
            $('#tiff_pages').val(data.tiff_pages)
            hideLoader()
          },
          error: function(xhr) {
            var errorMessage = 'An error occurred during the file upload'
            if (xhr.responseJSON && xhr.responseJSON.error) {
              errorMessage = xhr.responseJSON.error
            } else if (xhr.statusText) {
              errorMessage = xhr.statusText
            }
            $('#file-error').text(errorMessage).show()
            $('#file').val('')
            hideLoader()
          }
        })
      } else {
        $('#file-error').text('Please upload your file').show()
        $('#file').val('')
        hideLoader()
      }
    })

    $(document).on('click', '#remove-file', function() {
      if (confirm('Are you sure want to remove this file?')) {
        showLoader()
        $.ajax({
          data: {
            file: $(this).data('name'),
            tiff_pages: $('#tiff_pages').val(),
          },
          type: "DELETE",
          dataType: "json",
          url: `/deletefile`,
          success: function(data) {
            hideLoader()
            $('#file-input').show()
            $('#file-data').empty()
            $('#fileurl').val('')
            $('#file_name').val('')
            $('#uploaded_at').val('')
            $('#original_name').val('')
            $('#tiff_pages').val('')
          },
          error: function(error) {
            hideLoader()
          }
        })
      }
    })

    $('#state').on('change', function() {
      showLoader()
      const state = $(this).val()
      if (state) {
        $.ajax({
          type: "GET",
          dataType: "json",
          url: `/counties/${state}`,
          success: function(data) {
            $('#county').empty()
            $('#county').append('<option value="">Select County</option>')
            $.each(data, (key, value) => {
              $('#county').append('<option value="' + value.name + '">' + value.name + '</option>')
            })
            hideLoader()
          },
          error: function(error) {
            $('#county').empty()
            $('#county').append('<option value="">Select County</option>')
            hideLoader()
          }
        })
      } else {
        $('#county').empty()
        $('#county').append('<option value="">Select County</option>')
        hideLoader()
      }
    })

    $(document).on('change', '#completed_file', function() {
      const fileInput = this
      $('#completed-file-error').empty()
      if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0]

        if (file.type != 'application/pdf') {
          $('#completed-file-error').html('Only PDF files are allowed to upload').show()
          $('#completed_file').val('')
          return
        }

        if (file.size > 24 * 1024 * 1024) {
          $('#completed-file-error').html('The file size must not exceed 24MB').show()
          $('#completed_file').val('')
          return
        }

        showLoader()
        let formData = new FormData()
        formData.append('file', file)
        formData.append('admin_request_id', '{{ $request->id ?? 0 }}')
        $.ajax({
          type: 'POST',
          data: formData,
          url: '/uploadfile',
          contentType: false,
          processData: false,
          success: function(data) {
            $('#completed-file-input').hide()
            $('#completed-file-data').html(`<div class="upload-wrap">
              <div class="me-4">${data.uploaded_at_local}</div>
              <div class="me-3">
                <p class="mb-0">
                  <i class="bi bi-file-earmark-pdf"></i>
                  <a style="cursor:pointer" onclick="onViewFile('${data.file}')">${data.original_name}</a>
                </p>
                <p class="text-danger mb-0 small">Click link above to review document</p>
              </div>
              <a class="text-center text-danger remove-file" id="completed-remove-file" data-name="${data.file_name}">
                <i class="bi bi-trash"></i><br /><small>Remove</small>
              </a>
            </div>`)
            $('#completed_file').val('')
            $('#completed_file').removeAttr('required')
            $('#completed_fileurl').val(data.file)
            $('#completed_file_name').val(data.file_name)
            $('#completed_at').val(data.uploaded_at)
            $('#completed_original_name').val(data.original_name)
            hideLoader()
          },
          error: function(xhr) {
            var errorMessage = 'An error occurred during the file upload'
            if (xhr.responseJSON && xhr.responseJSON.error) {
              errorMessage = xhr.responseJSON.error
            } else if (xhr.statusText) {
              errorMessage = xhr.statusText
            }
            $('#completed-file-error').text(errorMessage).show()
            $('#completed_file').val('')
            hideLoader()
          }
        })
      } else {
        $('#completed-file-error').text('Please upload your file').show()
        $('#completed_file').val('')
        hideLoader()
      }
    })

    $(document).on('click', '#completed-remove-file', function() {
      if (confirm('Are you sure want to remove this file?')) {
        showLoader()
        $.ajax({
          data: {
            file: $(this).data('name'),
            admin_request_id: '{{ $request->id ?? 0 }}',
          },
          type: "DELETE",
          dataType: "json",
          url: `/deletefile`,
          success: function(data) {
            hideLoader()
            $('#completed-file-input').show()
            $('#completed-file-data').empty()
            $('#completed_fileurl').val('')
            $('#completed_file_name').val('')
            $('#completed_at').val('')
            $('#completed_original_name').val('')
          },
          error: function(error) {
            hideLoader()
          }
        })
      }
    })
  })

  function onCancelRequest() {
    if (confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.')) {
      $('#cancel-form').submit()
    }
  }
</script>
@endpush