<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class Faq extends Controller
{
  public function index()
  {
    return view('faq');
  }

  public function helpfulVideos()
  {
    return view('helpful-videos');
  }
}
