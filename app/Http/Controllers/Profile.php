<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class Profile extends Controller
{
  public function index()
  {
    return view('profile');
  }

  public function update(Request $request)
  {
    $authUser = Auth::user();
    $messages = [
      'first_name.required' => 'Enter first name',
      'last_name.required' => 'Enter last name',
      'email.required' => 'Enter email address',
      'email.email' => 'Enter a valid email address',
      'email.unique' => 'Email address is already registered',
      'phone.required' => 'Enter phone number',
      'company_name.required' => 'Enter company name',
      'street_address.required' => 'Enter street address',
      'city.required' => 'Enter city',
      'state.required' => 'Enter state',
      'zip_code.required' => 'Enter zip code',
    ];
    $rules = [
      'first_name' => ['required'],
      'last_name' => ['required'],
      'email' => ['required', 'email', 'unique:users,email,' . $authUser->id],
      'phone' => ['required'],
      'company_name' => ['required'],
      'street_address' => ['required'],
      'city' => ['required'],
      'state' => ['required'],
      'zip_code' => ['required'],
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->with('update', 'profile')->withInput();
    }

    $profile = $this->save($request->all(), $authUser->id);
    event(new Registered($profile));

    return redirect()->back()->with('message', 'Profile successfully updated')->with('update', 'profile');
  }

  protected function save(array $request, $authId)
  {
    $update = [
      'first_name' => $request['first_name'],
      'last_name' => $request['last_name'],
      'name' => $request['first_name'] . ' ' . $request['last_name'],
      'email' => $request['email'],
      'phone' => $request['phone'],
      'company_name' => $request['company_name'],
      'street_address' => $request['street_address'],
      'city' => $request['city'],
      'state' => $request['state'],
      'zip_code' => $request['zip_code'],
    ];
    return User::where('id', $authId)->update($update);
  }


  public function updatePassword(Request $request)
  {
    $messages = [
      'current_password.required' => 'Enter your current password',
      'current_password.min' => 'The current password must be at least 8 characters long',
      'new_password.required' => 'Enter a new password',
      'new_password.min' => 'The new password must be at least 8 characters long',
      'new_password.different' => 'The new password must be different from the current password',
      'new_password.confirmed' => 'The new password confirmation does not match',
    ];
    $rules = [
      'current_password' => ['required', 'string', 'min:8'],
      'new_password' => ['required', 'string', 'min:8', 'different:current_password', 'confirmed'],
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->with('update', 'password')->withInput();
    }

    $authUser = Auth::user();
    if (!Hash::check($request->input('current_password'), $authUser->password)) {
      return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect'])->with('update', 'password')->withInput();
    }

    $password = $this->savePassword($request->all(), $authUser->id);
    event(new Registered($password));

    return redirect()->back()->with('message', 'Password successfully changed')->with('update', 'password');
  }

  protected function savePassword(array $request, $authId)
  {
    $update = [
      'password' => Hash::make($request['new_password']),
    ];
    return User::where('id', $authId)->update($update);
  }
}
