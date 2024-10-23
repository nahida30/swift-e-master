@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', $request->tag_it.' #'.$request->count)

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => $request->tag_it.' #'.$request->count])

  <section class="section">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ $request->tag_it }} #{{ $request->count }}</h5>
        <div class="row">
          <div class="col-12 col-lg-6">
            <p class="text-success fs-5">Current Stage: {{ $request->status }}</p>
            <div class="mt-3">
              <p class="mb-1"><strong>Service Requested:</strong> Recording</p>
              <p class="mb-1"><strong>Document Type:</strong> {{ $requestTypes[$request->doc_type] }}</p>
              <p class="mb-1"><strong>State:</strong> {{ $request->state }}</p>
              <p class="mb-1"><strong>County:</strong> {{ $request->county }}</p>
              <p class="mb-1"><strong>Tag It:</strong> {{ $request->tag_it }} #{{ $request->count }}</p>
              <p class="mb-1"><strong>Created On:</strong> {{ $request->created_at_local }}</p>
              <p class="mb-0"><strong>Submitted By:</strong> {{ $request->user->name }}
            </div>
            <div class="mt-5">
              <p>
                @if($request->status == 'Sent')
              <form action="{{ route('requests.delete', $request->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.');">Cancel Request</button>
              </form>
              @endif
              <a class="btn btn-warning" onclick="onViewNote('{{ $request->id }}')">Swift-E Notes</a>
              </p>
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <p class="text-success fs-5">Thank You</p>
            <p>We will be swift</p>
            <p>
              <a onclick="history.back()" class="btn btn-secondary">Back</a>
              <a href="{{ route('requests.index') }}" class="btn btn-primary">Go to My Requests</a>
              <a href="{{ route('requests.add', 0) }}" class="btn btn-success">Create Another Request</a>
            </p>
            <p class="text-danger"><strong>A REQUEST CANNOT BE CANCELLED AFTER SUBMITTED TO THE CLERK FOR RECORDING</strong></p>
          </div>

          <div class="col-12 col-lg-6">
            <div class="mt-5">
              <p class="text-success fs-5 mb-3">Uploaded Files:</p>
              <div class="upload-wrap">
                <div class="me-4">{{ $request->uploaded_at_local }}</div>
                <div class="me-3">
                  <p class="mb-0">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <a style="cursor:pointer" onclick="onViewFile('{{ $request->file }}')">{{ $request->original_name }}</a>
                  </p>
                  <p class="text-danger mb-0 small">Click link above to review document</p>
                </div>
              </div>
            </div>
          </div>

          @if($request->status == 'In-Process')
          <div class="col-12 col-lg-6">
            <div class="mt-5">
              <p class="text-success fs-5 mb-3">Re-Upload New File:</p>
              <input type="hidden" id="fileurl2" name="fileurl2" value="{{ $request && $request->file2 ? $request->file2 : '' }}" />
              <input type="hidden" id="file_name2" name="file_name2" value="{{ $request && $request->file_name2 ? $request->file_name2 : '' }}" />
              <input type="hidden" id="uploaded_at2" name="uploaded_at2" value="{{ $request && $request->uploaded_at2 ? $request->uploaded_at2 : '' }}" />
              <input type="hidden" id="original_name2" name="original_name2" value="{{ $request && $request->original_name2 ? $request->original_name2 : '' }}" />
              <input type="hidden" id="tiff_pages2" name="tiff_pages2" value="{{ $request && $request->tiff_pages2 ? $request->tiff_pages2 : '' }}" />
              <div id="file-input2">
                @if(!$request->file2)
                <input type="file" class="form-control" id="file2" name="file2" accept="application/pdf" aria-describedby="file2HelpBlock" {{ $request && $request->file2 ? '' : 'required' }} />
                <div id="file2HelpBlock" class="form-text">Allowed Filetype: PDF. Allowed Filesize: 24MB Max.</div>
                @if ($errors->has('file2'))
                <div id="file-error2" class="invalid-feedback d-block">{{ $errors->first('file2') }}</div>
                @else
                <div id="file-error2" class="invalid-feedback">Please re-upload your file</div>
                @endif
                @endif
              </div>
              <div id="file-data2">
                @if($request && $request->file2)
                <div class="upload-wrap">
                  <div class="me-4">{{ $request->uploaded_at2_local }}</div>
                  <div class="me-3">
                    <p class="mb-0">
                      <i class="bi bi-file-earmark-pdf"></i>
                      <a style="cursor:pointer" onclick="onViewFile('{{ $request->file2 }}')">{{ $request->original_name2 }}</a>
                    </p>
                    <p class="text-danger mb-0 small">Click link above to review document</p>
                  </div>
                  <a class="text-center text-danger remove-file" id="remove-file2" data-name="{{ $request->file_name2 }}">
                    <i class="bi bi-trash"></i><br /><small>Remove</small>
                  </a>
                </div>
                @endif
              </div>
            </div>
          </div>
          @endif
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
    $(document).on('change', '#file2', function() {
      const fileInput = this
      $('#file-error2').empty()
      if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0]

        if (file.type != 'application/pdf') {
          $('#file-error2').html('Only PDF files are allowed to upload').show()
          $('#file2').val('')
          return
        }

        if (file.size > 24 * 1024 * 1024) {
          $('#file-error2').html('The file size must not exceed 24MB').show()
          $('#file2').val('')
          return
        }

        showLoader()
        let formData = new FormData()
        formData.append('file', file)
        formData.append('request_id', '{{ $request->id }}')
        $.ajax({
          type: 'POST',
          data: formData,
          url: '/uploadfile',
          contentType: false,
          processData: false,
          success: function(data) {
            hideLoader()
            if (data.type == 'error') {
              alert(data.text)
              return
            }

            $('#file-input2').hide()
            $('#file-data2').html(`<div class="upload-wrap">
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
            $('#file2').val('')
            $('#file2').removeAttr('required')
            $('#fileurl2').val(data.file)
            $('#file_name2').val(data.file_name)
            $('#uploaded_at2').val(data.uploaded_at)
            $('#original_name2').val(data.original_name)
            $('#tiff_pages2').val(data.tiff_pages)
          },
          error: function(xhr) {
            var errorMessage = 'An error occurred during the file upload'
            if (xhr.responseJSON && xhr.responseJSON.error) {
              errorMessage = xhr.responseJSON.error
            } else if (xhr.statusText) {
              errorMessage = xhr.statusText
            }
            $('#file-error2').text(errorMessage).show()
            $('#file2').val('')
            hideLoader()
          }
        })
      } else {
        $('#file-error2').text('Please upload your file').show()
        $('#file2').val('')
        hideLoader()
      }
    })

    $(document).on('click', '#remove-file2', function() {
      if (confirm('Are you sure want to remove this file?')) {
        showLoader()
        $.ajax({
          data: {
            file: $(this).data('name'),
            request_id: '{{ $request->id }}',
            tiff_pages: $('#tiff_pages2').val(),
          },
          type: "DELETE",
          dataType: "json",
          url: `/deletefile`,
          success: function(data) {
            hideLoader()
            if (data.type == 'error') {
              alert(data.text)
              return
            }

            $('#file-input2').html(`<input type="file" class="form-control" id="file2" name="file2" accept="application/pdf" aria-describedby="file2HelpBlock" required />
              <div id="file2HelpBlock" class="form-text">Allowed Filetype: PDF. Allowed Filesize: 24MB Max.</div>
              <div id="file-error2" class="invalid-feedback">Please re-upload your file</div>`)
            $('#file-input2').show()
            $('#file-data2').empty()
            $('#fileurl2').val('')
            $('#file_name2').val('')
            $('#uploaded_at2').val('')
            $('#original_name2').val('')
            $('#tiff_pages2').val('')
          },
          error: function(error) {
            hideLoader()
          }
        })
      }
    })
  })
</script>
@endpush