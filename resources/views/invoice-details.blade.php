@extends('shared.layout')

@push('styles')
<style>
  .invoice {
    padding: 1rem;
    margin: 0 auto;
    background: #f9f9f9;
  }

  .invoice-header {
    padding-bottom: 1rem;
    border-bottom: 2px solid #ddd;
  }

  .invoice-body {
    padding-top: 1rem;
  }
</style>
@endpush

@section('content')
<div class="invoice">
  <div class="invoice-header">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th class="text-start">
              <h3 class="m-0">Invoice</h3>
              <p class="m-0">Invoice #{{ $invoice->id }}</p>
              <p class="m-0">Date: {{ $invoice->due_date_local }}</p>
            </th>
            <th class="text-start">
              <p class="m-0">{{ $invoice->user->name }}</p>
              <p class="m-0">{{ $invoice->user->company_name }}</p>
              <p class="m-0">{{ $invoice->user->street_address }}, {{ $invoice->user->city }}, {{ $invoice->user->state }}, {{ $invoice->user->zip_code }}</p>
            </th>
            <th class="text-end">
              <img src="{{ asset('img/logo192.png') }}" alt="logo" width="70" />
            </th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <div class="invoice-body">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" width="10%">SL No</th>
            <th class="text-start" width="25%">Request</th>
            <th class="text-start" width="25%">Document</th>
            <th class="text-center" width="15%">County Recording Fee</th>
            <th class="text-center" width="15%">Swift-E Processing Fee</th>
            <th class="text-center" width="10%">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($invoice->requests as $i => $request)
          <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td class="text-start">{{ $request->tag_it }} #{{ $request->count }}</td>
            <td class="text-start">{{ $requestTypes[$request->doc_type] }}</td>
            <td class="text-end">$ {{ number_format($invoice->prices[$request->id], 2) }}</td>
            <td class="text-end">$ {{ number_format($swifteFee, 2) }}</td>
            <td class="text-end">$ {{ number_format($invoice->prices[$request->id] + $swifteFee, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td class="text-end" colspan="5"><strong>Grand Total</strong></td>
            <td class="text-end"><strong>$ {{ number_format($invoice->amount, 2) }}</strong></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <p>{{ $invoice->description }}</p>
  </div>
</div>
@endsection


@push('scripts')
<script>

</script>
@endpush