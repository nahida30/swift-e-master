@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Invoices - ' . $client->name)

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Invoices - ' . $client->name])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row py-3">
              <div class="col-12 col-md-6">
                @include('shared.clients-menu')
              </div>
              <div class="col-12 col-md-6">
                @if (session('message'))<small class="text-success fw-light">{!! session('message') !!}</small>@endif
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    <th class="text-center">Invoice #</th>
                    @if($user->isAdmin())<th>Username</th>@endif
                    <th>Requests</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($invoices as $invoice)
                  <tr>
                    <td class="text-center"><a style="cursor:pointer" class="pe-auto" onclick="onViewInvoice('{{ $invoice->id }}')">{{ $invoice->id }}</a></td>
                    @if($user->isAdmin())<td>{{ $invoice->user->name }}</td>@endif
                    <td>
                      @foreach($invoice->requests as $i => $request)
                      {{ $request->tag_it }} #{{ $request->count }}{{ $i < count($invoice->requests) - 1 ? ', ' : '' }}
                      @endforeach
                    </td>
                    <td>${{ $invoice->amount }}</td>
                    <td>{{ strtoupper($invoice->status) }}</td>
                    <td>{{ $invoice->created_at_local }}</td>
                    <td class="text-center">
                      @if($user->isAdmin())
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewInvoice('{{ $invoice->id }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Download" href="{{ asset('storage/invoices/invoice-' . $invoice->id.'.pdf') }}" target="_blank">
                        <i class="bi bi-download"></i>
                      </a>
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('invoices.add', $invoice->id) }}/?requests={{ implode(',', array_keys($invoice->prices)) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('invoices.delete', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete" onclick="return confirm('Are you sure you want to DELETE this Invoice? This action can not be undone if performed.');">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                      @endif
                      @if(!$user->isAdmin())
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewInvoice('{{ $invoice->id }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Download" href="{{ asset('storage/invoices/invoice-' . $invoice->id.'.pdf') }}" target="_blank">
                        <i class="bi bi-download"></i>
                      </a>
                      <a class="btn btn-success btn-sm" href="{{ route('invoices.payment', $invoice->id) }}">Pay Now</a>
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

@include('includes.modals.invoice-view')

@endsection


@push('scripts')
<script>

</script>
@endpush