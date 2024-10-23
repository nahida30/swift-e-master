<div class="modal fade" id="viewInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="viewInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="height:94%" role="document">
    <div class="modal-content overflow-hidden h-100">
      <a data-bs-dismiss="modal" aria-label="Close" class="btn btn-sm btn-danger position-absolute end-0 top-0 z-1">
        <i class="bi bi-x-lg"></i>
      </a>
      <div class="modal-body p-0">
        <iframe id="invoiceViewer" width="100%" height="100%" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function onViewInvoice(id) {
    showLoader()
    const invoiceViewer = document.getElementById('invoiceViewer')
    invoiceViewer.src = `/invoices/${id}/details`
    $('#viewInvoiceModal').modal('show')
    invoiceViewer.onload = () => {
      hideLoader()
    }
  }
</script>
@endpush