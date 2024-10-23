<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoteMail extends Mailable
{
  use Queueable, SerializesModels;

  public $note;
  public $tagIt;
  public $count;

  /**
   * Create a new message instance.
   */
  public function __construct($note, $tagIt, $count)
  {
    $this->note = $note;
    $this->tagIt = $tagIt;
    $this->count = $count;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject('Re: ' . $this->tagIt . ' #' . $this->count)
      ->view('emails.note');
  }
}
