<div class="modal fade" id="viewNoteModal" tabindex="-1" role="dialog" aria-labelledby="viewNoteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content h-100">
      <form id="note-form" novalidate>
        <div class="modal-header">
          <div class="w-100">
            <div class="input-group pe-4">
              <input type="hidden" id="request_id" name="request_id" />
              <input type="hidden" id="sender_id" name="sender_id" value="{{ $user->id }}" />
              <textarea id="note" name="note" class="form-control" placeholder="Add Note" aria-label="Add Note" aria-describedby="send-note" required></textarea>
              <button class="btn btn-primary" type="submit" id="send-note">Send</button>
            </div>
            @if($user->isAdmin())
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="issue" id="issue" />
              <label class="form-check-label" for="issue">This is an issue note</label>
            </div>
            @endif
          </div>
          <a data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-danger position-absolute end-0 top-0 z-1">
            <i class="bi bi-x-lg"></i>
          </a>
        </div>
      </form>
      <div class="modal-body">
        <ul class="list-unstyled m-0" id="notes-body">
          @if(isset($request->notes) && count($request->notes))
          @foreach($request->notes as $note)
          <li class="d-flex justify-content-{{ $note->sender_id == $user->id ? 'start' : 'end' }} text-{{ $note->sender_id == $user->id ? 'start' : 'end' }} mb-3 p{{ $note->sender_id == $user->id ? 'e' : 's' }}-5">
            @if($note->sender_id == $user->id)
            <img src="{{ asset('img/profile-img.png') }}" alt="Me" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="48" />
            @endif
            <div class="bg-light py-2 px-3 rounded">
              <p class="fw-bold mb-0">{{ $note->sender_id == $user->id ? 'Me' : $note->sender->name }}</p>
              <p class="text-start" style="white-space:break-spaces">@if($note->issue)<span class="text-danger me-2" data-toggle="tooltip" data-placement="top" title="Issue"><i class="bi bi-info-circle"></i></span>@endif{{ $note->note }}</p>
              <p class="text-muted small m-0">{{ $note->created_at_local }}</p>
            </div>
            @if($note->sender_id != $user->id)
            <img src="{{ asset('img/profile-img.png') }}" alt="{{ $note->sender->name }}" class="rounded-circle d-flex align-self-start ms-3 shadow-1-strong" width="48" />
            @endif
          </li>
          @endforeach
          @else
          <h5 class="text-center mt-3">No Notes Added</h5>
          @endif
        </ul>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function onViewNote(id) {
    $('#request_id').val(id)
    $('.invalid-feedback').text('')
    $('#viewNoteModal').modal('show')
    $('.form-control').removeClass('is-invalid')
  }

  $(document).ready(function() {
    "@if(request()->query('note') || request()->query('issue'))"
    onViewNote('{{ $request->id }}')
    "@endif"

    $('#note-form').on('submit', function(e) {
      e.preventDefault()

      showLoader()
      $('.form-control').removeClass('is-invalid')
      $('.invalid-feedback').text('')

      $.ajax({
        type: "POST",
        data: {
          note: $('#note').val(),
          sender_id: $('#sender_id').val(),
          request_id: $('#request_id').val(),
          issue: $('#issue').prop('checked') || 0,
        },
        url: `{{ route('notes.save') }}`,
        success: function(data) {
          hideLoader()
          $('#note').val('')
          $('#issue').prop('checked', false)

          let noteItem = `<li class="d-flex justify-content-start text-start mb-3 pe-5">
          <img src="{{ asset('img/profile-img.png') }}" alt="Me" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="48" />
          <div class="bg-light py-2 px-3 rounded">
            <p class="fw-bold mb-0">Me</p>
            <p class="text-start" style="white-space:break-spaces">${data.note.note}</p>
            <p class="text-muted small m-0">${data.note.created_at_local}</p>
          </div>
        </li>`
          $('#notes-body').prepend(noteItem)
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            var errors = xhr.responseJSON.errors
            $.each(errors, function(key, value) {
              $('#' + key).addClass('is-invalid')
            })
          }
          hideLoader()
        }
      })
    })

    const viewNoteModal = document.getElementById('viewNoteModal')
    viewNoteModal.addEventListener('shown.bs.modal', () => {
      $.ajax({
        type: "POST",
        data: {
          request_id: $('#request_id').val(),
        },
        url: `{{ route('notes.read') }}`,
        success: function(data) {
          if (data.count > 0) {
            let notes = ``
            $('#notes-count').text(data.count)
            $('#notes-dropdown').html(data.notes_html)
          } else {
            $('#notes-count').hide()
            $('#notes-dropdown').remove()
          }
        }
      })

      viewNoteModal.addEventListener('hidden.bs.modal', () => {
        const currentUrl = new URL(window.location.href)
        currentUrl.searchParams.delete('issue')
        currentUrl.searchParams.delete('note')
        const newUrl = currentUrl.toString()
        history.replaceState(null, '', newUrl)
      })
    })
  })
</script>
@endpush