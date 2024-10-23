@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Add Invoice')

@push('styles')
<style>
  #due_date {
    text-transform: uppercase;
  }
</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Add Invoice'])

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center mb-0">
              <div class="d-flex align-items-center">Add Invoice</div>
              <div></div>
            </h5>
            <form id="request-form" action="{{ route('invoices.submit') }}" method="POST" class="needs-validation {{ count($errors) ? 'was-validated' : '' }}" novalidate>
              @csrf
              <div class="row mb-3">
                <label for="company_name" class="col-sm-2 col-form-label">Company:</label>
                <div class="col-sm-4">
                  <div class="form-control">{{ $requestUser->company_name }}</div>
                </div>
                <label for="client_type" class="col-sm-2 col-form-label">Client Type:</label>
                <div class="col-sm-4">
                  <div class="form-control">{{ $requestUser->client_type }}</div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="due_date" class="col-sm-2 col-form-label">Invoice Due Date<small class="text-danger">*</small></label>
                <div class="col-sm-10">
                  <input type="datetime-local" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', ($invoice && $invoice->due_date) ? $invoice->due_date_local : $nowDateTime) }}" required />
                  @if ($errors->has('due_date'))
                  <div class="invalid-feedback d-block">{{ $errors->first('due_date') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter invoice due date</div>
                  @endif
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-3">
                  <strong>Requests to Invoice</strong>
                </div>
                <div class="col-sm-3">
                  <strong>Document</strong>
                </div>
                <div class="col-sm-2">
                  <strong>County Recording Fee</strong>
                </div>
                <div class="col-sm-2">
                  <strong>Swift-E Processing Fee</strong>
                </div>
                <div class="col-sm-2">
                  <strong>Total</strong>
                </div>
              </div>
              <div class="border mb-3">
                <div class="p-3">
                  @foreach($requests as $i => $request)
                  <input type="hidden" name="user_id" id="user_id" value="{{ $request->user_id }}" />
                  <div class="row {{ $i < count($requests) - 1 ? 'mb-3' : '' }}">
                    <label for="price_{{ $request->id }}" class="col-sm-3 col-form-label">
                      {{ $i + 1}}. {{ $request->tag_it }} #{{ $request->count }}<small class="text-danger">*</small>
                    </label>
                    <label class="col-sm-3 col-form-label">{{ $requestTypes[$request->doc_type] }}</label>
                    <div class="col-sm-2">
                      <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control price-input" data-id="{{ $request->id }}" id="price_{{ $request->id }}" name="prices[{{ $request->id }}]" value="{{ old('prices['.$request->id.']', ($invoice && $invoice->prices) ? $invoice->prices[$request->id] : '') }}" required />
                      </div>
                      @if ($errors->has('prices'))
                      <div class="invalid-feedback d-block">{{ $errors->first('prices') }}</div>
                      @else
                      <div class="invalid-feedback">Please enter a price</div>
                      @endif
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" id="fee_{{ $request->id }}" value="{{ $swifteFee }}" readonly />
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control total-input" id="total_{{ $request->id }}" value="{{ ($invoice && $invoice->prices) ? ($invoice->prices[$request->id] + $swifteFee) : $swifteFee }}" readonly />
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <hr class="m-0" />
                <div class="p-3">
                  <div class="row">
                    <label for="amount" class="col-sm-6 col-form-label"><strong>Grand Amount</strong><small class="text-danger">*</small></label>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2">
                      <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="text" class="form-control" id="amount" name="amount" value="{{ old('amount', ($invoice && $invoice->amount) ? $invoice->amount : '') }}" required readonly />
                      </div>
                      @if ($errors->has('amount'))
                      <div class="invalid-feedback d-block">{{ $errors->first('amount') }}</div>
                      @else
                      <div class="invalid-feedback">Please enter an amount</div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="description" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="description" name="description">{{ old('description', ($invoice && $invoice->description) ? $invoice->description : '') }}</textarea>
                  @if ($errors->has('description'))
                  <div class="invalid-feedback d-block">{{ $errors->first('description') }}</div>
                  @else
                  <div class="invalid-feedback">Please enter description</div>
                  @endif
                </div>
              </div>
              <div class="row mt-5">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  @if($invoice && $invoice->id)
                  <input type="hidden" name="id" value="{{ $invoice->id }}" />
                  @endif

                  @if(!$invoice)
                  <button type="submit" class="btn btn-primary">Create</button>
                  @endif

                  @if($invoice && $invoice->status != 'paid')
                  <button type="submit" class="btn btn-primary">Update</button>
                  @endif

                  <a onclick="history.back()" class="btn btn-secondary">Cancel</a>

                  @if (session('message'))<small class="text-success ms-3 fw-light">{!! session('message') !!}</small>@endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection


@push('scripts')
<script>
  $(document).ready(function() {
    function calculateTotal(e) {
      let total = 0
      let reqId = $(e).data('id')
      let fee = parseFloat($(`#fee_${reqId}`).val())
      let price = parseFloat($(`#price_${reqId}`).val())
      if (!isNaN(fee) && !isNaN(price)) {
        total = fee + price
      }
      $(`#total_${reqId}`).val(total ? total.toFixed(2) : '')
    }

    function calculateGrandTotal() {
      let grandTotal = 0
      $('.total-input').each(function() {
        let total = parseFloat($(this).val())
        if (!isNaN(total)) {
          grandTotal += total
        }
      })
      $('#amount').val(grandTotal ? grandTotal.toFixed(2) : '')
    }

    $('.price-input').on('change', function() {
      calculateTotal(this)
      calculateGrandTotal()
    })

    calculateGrandTotal()
  })
</script>
@endpush