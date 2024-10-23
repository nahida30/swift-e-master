<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Invoice;
use App\Helpers\Timezone;
use App\Mail\InvoiceMail;
use Illuminate\Http\Request;
use App\Helpers\RequestType;
use App\Rules\PricesRequired;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session as CheckoutSession;

class Invoices extends Controller
{
  public function index()
  {
    $authUser = Auth::user();
    $invoices = Invoice::orderBy('id', 'desc');
    if (!$authUser->isAdmin()) {
      $invoices->where('user_id', $authUser->id);
    }
    $invoices = $invoices->get();

    return view('invoices', [
      'invoices' => $invoices,
    ]);
  }

  public function add($id, Request $request)
  {
    $invoice = null;
    if ($id) {
      $invoice = Invoice::findOrFail($id);
      $requestIds = implode(',', array_keys($invoice->prices));
    } else {
      $requestIds = $request->input('requests');
    }

    if (!$requestIds) {
      return redirect()->back();
    }

    $userIds = [];
    $authUser = Auth::user();
    $requestIds = explode(',', $requestIds);
    $requests = ModelsRequest::whereIn('id', $requestIds)->get();
    foreach ($requests as $req) {
      $userIds[] = $req->user_id;
    }

    if (count(array_unique($userIds)) > 1) {
      return redirect()->back()->with('message', 'Please choose the requests from same user');
    }

    $requestTypes = RequestType::get();
    $availableFees = RequestType::prices();
    $requestUser = User::findOrFail($userIds[0]);
    $swifteFee = $availableFees[$requestUser->client_type];
    $nowDateTime = Timezone::toLocal(date('Y-m-d H:i:s'), $authUser->timezone_offset);

    return view('invoice-add', [
      'invoice' => $invoice,
      'requests' => $requests,
      'requestUser' => $requestUser,
      'nowDateTime' => $nowDateTime,
      'requestTypes' => $requestTypes,
      'swifteFee' => $swifteFee,
    ]);
  }

  public function submit(Request $request)
  {
    $this->validator($request->all())->validate();

    $authUser = Auth::user();
    $dueDateLocal = $request->input('due_date');
    $requestUser = User::findOrFail($request->input('user_id'));
    $dueDateUtc = Timezone::toUtc($dueDateLocal, $authUser->timezone_offset, 'Y-m-d H:i:s');

    $where = [
      'id' => $request['id'] ?? 0,
    ];
    $update = [
      'due_date' => $dueDateUtc,
      'amount' => $request->input('amount'),
      'prices' => $request->input('prices'),
      'user_id' => $request->input('user_id'),
      'description' => $request->input('description'),
    ];
    $newInvoice = Invoice::updateOrCreate($where, $update);

    $requestTypes = RequestType::get();
    $availableFees = RequestType::prices();
    $swifteFee = $availableFees[$requestUser->client_type];
    $invoicePath = storage_path('app/public/invoices/invoice-' . $newInvoice->id . '.pdf');
    if (!file_exists(dirname($invoicePath))) {
      mkdir(dirname($invoicePath), 0755, true);
    }

    $pdf = PDF::loadView('includes.pdfs.invoice', [
      'invoice' => $newInvoice,
      'swifteFee' => $swifteFee,
      'requestTypes' => $requestTypes,
    ])->setPaper('a4', 'landscape');

    file_put_contents($invoicePath, $pdf->output());

    Mail::to($requestUser->email)->send(new InvoiceMail($newInvoice, $invoicePath));
    event(new Registered($newInvoice));

    if (!$request->input('id')) {
      $message = 'Invoice successfully created';
    } else {
      $message = 'Invoice successfully updated';
    }

    return redirect()->route('invoices.add', $newInvoice->id)->with('message', $message);
  }

  protected function validator(array $request)
  {
    $messages = [
      'due_date.required' => 'Please enter invoice due date',
      'prices.required' => 'Please enter a price',
      'amount.required' => 'Please enter an amount',
    ];

    $rules = [
      'due_date' => ['required'],
      'prices' => ['required', new PricesRequired],
      'amount' => ['required'],
    ];

    return Validator::make($request, $rules, $messages);
  }

  public function details($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $requestTypes = RequestType::get();
    $invoice = Invoice::findOrFail($id);
    $availableFees = RequestType::prices();
    $requestUser = User::findOrFail($invoice->user_id);
    $swifteFee = $availableFees[$requestUser->client_type];

    return view('invoice-details', [
      'invoice' => $invoice,
      'swifteFee' => $swifteFee,
      'requestTypes' => $requestTypes,
    ]);
  }

  public function payment($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    $invoice = Invoice::findOrFail($id);
    if ($authUser->role == 'user' && $authUser->id != $invoice->user_id) {
      return redirect()->back();
    }

    Stripe::setApiKey(env('STRIPE_SECRET'));

    $productNames = [];
    $productDescs = [];
    $requestTypes = RequestType::get();
    $requestIds = array_keys($invoice->prices);
    $requests = ModelsRequest::whereIn('id', $requestIds)->get();
    foreach ($requests as $request) {
      $productDescs[] = $requestTypes[$request->doc_type];
      $productNames[] = $request->tag_it . ' #' . $request->count;
    }

    $checkout_session = CheckoutSession::create([
      'payment_method_types' => ['card'],
      'customer_email' => $authUser->email,
      'metadata' => [
        'customer_name' => $authUser->first_name . ' ' . $authUser->last_name,
      ],
      'line_items' => [
        [
          'price_data' => [
            'currency' => 'usd',
            'unit_amount' => $invoice->amount * 100,
            'product_data' => [
              'name' => implode(', ', $productNames),
              'description' => implode(', ', $productDescs),
            ],
          ],
          'quantity' => 1,
        ],
      ],
      'mode' => 'payment',
      'success_url' => route('invoice.success') . '?invoice_id=' . $id . '&session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => route('invoice.cancel') . '?invoice_id=' . $id . '&session_id={CHECKOUT_SESSION_ID}',
    ]);
    return redirect()->away($checkout_session->url);
  }

  public function delete($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    if (!$authUser->isAdmin()) {
      return redirect()->back();
    }

    $invoice = Invoice::findOrFail($id);
    if (!$invoice) {
      return redirect()->back();
    }

    $invoice->delete();

    return redirect()->back()->with('message', 'Invoice successfully deleted');
  }


  public function invoiceCancel(Request $request)
  {
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $invoiceId = $request->get('invoice_id', 0);
    $paymentId = $request->get('session_id', null);

    $payment = CheckoutSession::retrieve($paymentId);

    $invoice = Invoice::findOrFail($invoiceId);
    $invoice->status = 'pending';
    $invoice->details = [
      'payment' => $payment,
    ];
    $invoice->save();

    return redirect()->route('invoices.index')->with('message', 'Invoice payment failed');
  }

  public function invoiceSuccess(Request $request)
  {
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $invoiceId = $request->get('invoice_id', 0);
    $paymentId = $request->get('session_id', null);

    $payment = CheckoutSession::retrieve($paymentId);

    $invoice = Invoice::findOrFail($invoiceId);
    $invoice->status = $payment->payment_status ?? 'pending';
    $invoice->details = [
      'payment' => $payment,
    ];
    $invoice->save();

    return redirect()->route('invoices.index')->with('message', 'Invoice successfully paid');
  }
}
