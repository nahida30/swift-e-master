<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Helpers\OTP;
use App\Models\User;
use App\Mail\OtpMail;
use Stripe\Subscription;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session as CheckoutSession;

class Signup extends Controller
{
  private $productId = 'prod_QQQgRHuhuD1zEu';
  private $priceId = 'price_1PZZrX2KVs6RDoCOyXk48ZeJ';

  public function index()
  {
    return view('signup');
  }

  public function submit(Request $request)
  {
    $this->validator($request->all())->validate();

    if (!Session::has('signup_step')) {
      $otp = OTP::generateOTP();
      Session::put('otp', $otp);
      Session::put('signup_step', 'otp');
      Session::put('user', $request->all());
      Mail::to($request->input('email'))->send(new OtpMail($otp));

      return redirect()->back()->withInput();
    } else {
      $sessionOtp = Session::get('otp');
      if ($request->input('otp') === $sessionOtp) {
        Session::put('signup_step', 'membership');
        Session::put('otp_entered', $request->input('otp'));

        if ($request->input('client_type')) {
          $newUser = $this->create($request->all());

          // Redirect to payment page for Gold Member
          if ($request->input('client_type') == 'Gold Member') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $checkout_session = CheckoutSession::create([
              'payment_method_types' => ['card'],
              'customer_email' => $request->input('email'),
              'metadata' => [
                'customer_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
              ],
              'line_items' => [
                [
                  'price' => $this->priceId,
                  'quantity' => 1,
                ],
              ],
              'mode' => 'subscription',
              'success_url' => route('signup.success') . '?user_id=' . $newUser->id . '&session_id={CHECKOUT_SESSION_ID}',
              'cancel_url' => route('signup.cancel') . '?user_id=' . $newUser->id . '&session_id={CHECKOUT_SESSION_ID}',
            ]);
            return redirect()->away($checkout_session->url);
          } else {
            event(new Registered($newUser));
            return $this->autoLogin($request->all());
          }
        } else {
          return redirect()->back()->withInput();
        }
      } else {
        return redirect()->back()->withInput()->withErrors(['otp' => 'An invalid code entered']);
      }
    }
  }

  private function autoLogin(array $user)
  {
    $credentials = [
      'email' => $user['email'],
      'password' => $user['password'],
    ];
    if (Auth::attempt($credentials)) {
      session()->regenerate();
      Session::forget(['user', 'otp', 'otp_entered', 'signup_step']);
      return redirect()->route('dashboard.index');
    }
  }

  protected function validator(array $user)
  {
    $messages = [
      'first_name.required' => 'Enter your first name',
      'last_name.required' => 'Enter your last name',
      'email.required' => 'Enter your email address',
      'email.email' => 'Enter a valid email address',
      'email.unique' => 'Email address is already registered',
      'password.required' => 'Enter a password',
      'password.min' => 'Password must be at least 8 characters long',
      'phone.required' => 'Enter your phone number',
      'company_name.required' => 'Enter company name',
      'street_address.required' => 'Enter street address',
      'city.required' => 'Enter city',
      'state.required' => 'Enter state',
      'zip_code.required' => 'Enter zip code',
      'terms.accepted' => 'Agree the terms before submitting',
      'otp.required' => 'Enter the code',
      'otp.min' => 'An invalid code entered',
      'otp.max' => 'An invalid code entered',
      'client_type.required' => 'Choose a membership package',
    ];

    $rules = [
      'first_name' => ['required', 'max:125'],
      'last_name' => ['required', 'max:125'],
      'email' => ['required', 'email', 'unique:users'],
      'password' => ['required', 'string', 'min:8'],
      'phone' => ['required'],
      'company_name' => ['required'],
      'street_address' => ['required'],
      'city' => ['required'],
      'state' => ['required'],
      'zip_code' => ['required'],
      'terms' => ['accepted'],
    ];
    if (Session::get('signup_step') == 'otp') {
      $rules['otp'] = ['required', 'min:6', 'max:6'];
    }
    if (Session::get('signup_step') == 'membership') {
      $rules['client_type'] = ['required'];
    }

    return Validator::make($user, $rules, $messages);
  }

  protected function create($request)
  {
    return User::create([
      'first_name' => $request['first_name'],
      'last_name' => $request['last_name'],
      'name' => $request['first_name'] . ' ' . $request['last_name'],
      'email' => $request['email'],
      'email_verified_at' => date('Y-m-d H:i:s'),
      'password' => Hash::make($request['password']),
      'phone' => $request['phone'],
      'company_name' => $request['company_name'],
      'street_address' => $request['street_address'],
      'city' => $request['city'],
      'state' => $request['state'],
      'zip_code' => $request['zip_code'],
      'otp' => Session::get('otp'),
      'client_type' => $request['client_type'],
      'timezone_offset' => $request['timezone_offset'],
    ]);
  }

  public function signupCancel(Request $request)
  {
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $userId = $request->get('user_id', 0);
    $paymentId = $request->get('session_id', null);

    $payment = CheckoutSession::retrieve($paymentId);

    $user = User::findOrFail($userId);
    $user->payment_status = 'pending';
    $user->membership_status = 'inactive';
    $user->membership_start = null;
    $user->membership_end = null;
    $user->details = [
      'payment' => $payment,
    ];
    $user->save();

    Session::forget(['user', 'otp', 'otp_entered', 'signup_step']);
    return view('checkout-cancel', [
      'status' => 'canceled',
    ]);
  }

  public function signupSuccess(Request $request)
  {
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $userId = $request->get('user_id', 0);
    $paymentId = $request->get('session_id', null);

    $payment = CheckoutSession::retrieve($paymentId);
    $subscription = Subscription::retrieve($payment->subscription);

    $user = User::findOrFail($userId);
    $user->payment_status = $payment->status ?? 'pending';
    $user->membership_status = $subscription->status ?? 'inactive';
    $user->membership_start = date('Y-m-d H:i:s', $subscription->current_period_start);
    $user->membership_end = date('Y-m-d H:i:s', $subscription->current_period_end);
    $user->details = [
      'payment' => $payment,
      'subscription' => $subscription,
    ];
    $user->save();

    $sesUser = Session::get('user');
    return $this->autoLogin($sesUser);
  }
}
