<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Timezone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
  public function index()
  {
    Session::forget(['user', 'otp', 'otp_entered', 'signup_step']);
    return view('login');
  }

  public function submit(Request $request)
  {
    $this->validator($request->all())->validate();

    $rememberme = $request->has('rememberme');
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $rememberme)) {
      $request->session()->regenerate();
      Timezone::updateTzOffset($request->input('timezone_offset'), $request->input('email'));
      return redirect()->route('dashboard.index');
    }

    return redirect()->back()->withErrors(['password' => 'An invalid credentials entered']);
  }

  protected function validator(array $user)
  {
    $messages = [
      'email.required' => 'Please enter your email address',
      'email.email' => 'Please enter a valid email address',
      'password.required' => 'Please enter your password',
    ];

    $rules = [
      'email' => ['required', 'email'],
      'password' => ['required'],
    ];

    return Validator::make($user, $rules, $messages);
  }
}
