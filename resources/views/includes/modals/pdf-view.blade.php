<div class="modal fade" id="viewFileModal" tabindex="-1" role="dialog" aria-labelledby="viewFileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="height:94%" role="document">
    <div class="modal-content h-100">
      <a data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-danger position-absolute end-0 top-0 z-1">
        <i class="bi bi-x-lg"></i>
      </a>
      <div class="modal-body">
        <iframe id="pdfViewer" width="100%" height="100%" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function onViewFile(file) {
    showLoader()
    const pdfViewer = document.getElementById('pdfViewer')
    pdfViewer.src = `https://docs.google.com/gview?url=${file}&embedded=true`
    $('#viewFileModal').modal('show')
    pdfViewer.onload = () => {
      hideLoader()
    }
  }
</script>
@endpush