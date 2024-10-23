<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class Aboutus extends Controller
{
  public function index()
  {
    return view('aboutus');
  }
}
