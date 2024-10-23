<?php

namespace App\Mail;

use App\Helpers\RequestType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRequestMail extends Mailable
{
  use Queueable, SerializesModels;

  public $count;
  public $tagIt;
  public $docType;

  /**
   * Create a new message instance.
   */
  public function __construct($count, $tagIt, $docType)
  {
    $this->count = $count;
    $this->tagIt = $tagIt;
    $requestTypes = RequestType::get();
    $this->docType = $requestTypes[$docType];
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject($this->tagIt . ' #' . $this->count . ' ERECORDING')
      ->view('emails.new-request');
  }
}
