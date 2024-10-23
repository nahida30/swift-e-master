<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class Pricing extends Controller
{
  public function index()
  {
    return view('pricing');
  }
}
