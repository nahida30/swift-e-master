<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Invoice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .invoice {
      width: 80%;
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
</head>

<body>
  <div class="invoice">
    <div class="invoice-header">
      <table width="100%" cellspacing="0" cellpadding="5">
        <thead>
          <tr>
            <th align="left">
              <h2 style="margin:0">Invoice</h2>
              <p style="margin:0">Invoice #{{ $invoice->id }}</p>
              <p style="margin:0">Date: {{ $invoice->user_due_date_local }}</p>
            </th>
            <th align="left">
              <p style="margin:0">{{ $invoice->user->name }}</p>
              <p style="margin:0">{{ $invoice->user->company_name }}</p>
              <p style="margin:0">{{ $invoice->user->street_address }}, {{ $invoice->user->city }}, {{ $invoice->user->state }}, {{ $invoice->user->zip_code }}</p>
            </th>
            <th align="right">
              <img src="{{ asset('img/logo192.png') }}" alt="logo" width="70" />
            </th>
          </tr>
        </thead>
      </table>
    </div>
    <div class="invoice-body">
      <table width="100%" cellspacing="0" cellpadding="5" border="1" bordercolor="#ddd">
        <thead>
          <tr>
            <th align="center" width="10%">SL No</th>
            <th align="left" width="25%">Request</th>
            <th align="left" width="25%">Document</th>
            <th align="center" width="15%">County Recording Fee</th>
            <th align="center" width="15%">Swift-E Processing Fee</th>
            <th align="center" width="10%">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($invoice->requests as $i => $request)
          <tr>
            <td align="center">{{ $i + 1 }}</td>
            <td align="left">{{ $request->tag_it }} #{{ $request->count }}</td>
            <td align="left">{{ $requestTypes[$request->doc_type] }}</td>
            <td align="right">$ {{ number_format($invoice->prices[$request->id], 2) }}</td>
            <td align="right">$ {{ number_format($swifteFee, 2) }}</td>
            <td align="right">$ {{ number_format($invoice->prices[$request->id] + $swifteFee, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td align="right" colspan="5"><strong>Grand Total</strong></td>
            <td align="right"><strong>$ {{ number_format($invoice->amount, 2) }}</strong></td>
          </tr>
        </tfoot>
      </table>
      <p>{{ $invoice->description }}</p>
    </div>
  </div>
</body>

</html>