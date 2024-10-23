<?php

namespace App\Mail;

use App\Helpers\RequestType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
  use Queueable, SerializesModels;

  public $invoice;
  public $swifteFee;
  public $invoicePath;
  public $requestTypes;

  /**
   * Create a new message instance.
   */
  public function __construct($invoice, $invoicePath)
  {
    $this->invoice = $invoice;
    $this->invoicePath = $invoicePath;
    $availableFees = RequestType::prices();
    $this->requestTypes = RequestType::get();
    $requestUser = User::findOrFail($invoice->user_id);
    $this->swifteFee = $availableFees[$requestUser->client_type];
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject('Invoice')
      ->view('emails.invoice')
      ->attach($this->invoicePath, [
        'as' => 'invoice-' . $this->invoice->id . '.pdf',
        'mime' => 'application/pdf',
      ]);
  }
}
