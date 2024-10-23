<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class Users extends Controller
{
  public function index()
  {
    $authUser = Auth::user();
    if ($authUser->role != 'admin') {
      return redirect()->route('dashboard.index');
    }

    $members = User::where('role', 'non-admin')
      ->orderBy('id', 'desc')
      ->get();

    return view('users', [
      'members' => $members
    ]);
  }

  public function add($id)
  {
    $member = null;
    if ($id) {
      $member = User::where('role', 'non-admin')->findOrFail($id);
    }

    return view('user-add', [
      'member' => $member,
    ]);
  }

  public function submit(Request $request)
  {
    $this->validator($request->all())->validate();

    $member = $this->create($request->all());
    event(new Registered($member));

    if (!$request->input('id')) {
      $message = 'User successfully added';
    } else {
      $message = 'User successfully updated';
    }

    return redirect()->back()->with('message', $message);
  }

  protected function validator(array $request)
  {
    $messages = [
      'first_name.required' => 'Please enter first name',
      'last_name.required' => 'Please enter last name',
      'email.required' => 'Please enter email address',
      'email.email' => 'Please enter a valid email address',
      'email.unique' => 'This email address is already registered',
      'phone.required' => 'Please enter phone number',
      'password.required' => 'Please enter a password',
      'password.min' => 'Password must be at least 8 characters long',
    ];

    $rules = [
      'first_name' => ['required'],
      'last_name' => ['required'],
      'email' => ['required', 'email', 'unique:users,email' . (isset($request['id']) ? ',' . $request['id'] : '')],
    ];

    if (!isset($request['id'])) {
      $rules['password'] = ['required', 'string', 'min:8'];
    }

    $rules['phone'] = ['required'];

    return Validator::make($request, $rules, $messages);
  }

  protected function create(array $request)
  {
    $where = [
      'role' => 'non-admin',
      'id' => $request['id'] ?? 0,
    ];
    $update = [
      'first_name' => $request['first_name'],
      'last_name' => $request['last_name'],
      'name' => $request['first_name'] . ' ' . $request['last_name'],
      'email' => $request['email'],
      'password' => Hash::make($request['password']),
      'phone' => $request['phone'],
    ];
    return User::updateOrCreate($where, $update);
  }


  public function delete($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    if ($authUser->role != 'admin') {
      return redirect()->back();
    }

    $member = User::where('role', 'non-admin')->findOrFail($id);
    if (!$member) {
      return redirect()->back();
    }

    $member->delete();
    return redirect()->back()->with('message', 'User deleted successfully');
  }


  public function password($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $member = User::where('role', 'non-admin')->findOrFail($id);

    return view('user-password', [
      'member' => $member,
    ]);
  }

  public function passwordSave(Request $request, $id)
  {
    $messages = [
      'new_password.required' => 'Please enter a new password',
      'new_password.min' => 'The new password must be at least 8 characters long',
      'new_password.confirmed' => 'The new password confirmation does not match',
    ];
    $rules = [
      'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $password = $this->savePassword($request->all(), $id);
    event(new Registered($password));

    return redirect()->back()->with('message', 'Password successfully changed');
  }

  protected function savePassword(array $request, $memberId)
  {
    $update = [
      'password' => Hash::make($request['new_password']),
    ];
    return User::where('id', $memberId)->update($update);
  }
}
