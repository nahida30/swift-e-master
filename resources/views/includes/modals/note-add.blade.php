<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="note-form" novalidate>
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addNoteModalLabel">Add Note</h1>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <input type="hidden" id="request_id" name="request_id" />
            <input type="hidden" id="sender_id" name="sender_id" value="{{ $user->id }}" />
            <textarea class="form-control" id="note" name="note" required></textarea>
            <div class="invalid-feedback" id="note-error">Please write your note</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
          <button type="submit" class="btn btn-primary">Save Note</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function onAddNote(id) {
    $('#request_id').val(id)
    $('.form-control').removeClass('is-invalid')
    $('.invalid-feedback').text('')
    $('#addNoteModal').modal('show')
  }

  $('#note-form').on('submit', function(e) {
    e.preventDefault()

    showLoader()
    $('.form-control').removeClass('is-invalid')
    $('.invalid-feedback').text('')

    $.ajax({
      type: "POST",
      data: $(this).serialize(),
      url: `{{ route('notes.save') }}`,
      success: function(data) {
        hideLoader()
        $.toast(data)
        $('#note').val('')
        $('#addNoteModal').modal('hide')
        window.location.reload()
      },
      error: function(xhr) {
        if (xhr.status === 422) {
          var errors = xhr.responseJSON.errors
          $.each(errors, function(key, value) {
            $('#' + key).addClass('is-invalid')
            $('#' + key + '-error').text(value[0])
          })
        }
        hideLoader()
      }
    })
  })
</script>
@endpush