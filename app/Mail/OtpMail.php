<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
  use Queueable, SerializesModels;

  public $otp;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($otp)
  {
    $this->otp = $otp;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject('6 Digit Code for Signup')
      ->view('emails.otp');
  }
}
