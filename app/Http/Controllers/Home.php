<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class Home extends Controller
{
  public function index()
  {
    Session::forget(['user', 'otp', 'otp_entered', 'signup_step']);
    return view('home');
  }
}
