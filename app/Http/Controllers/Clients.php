<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Helpers\RequestType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Models\Request as ModelsRequest;
use Illuminate\Support\Facades\Validator;

class Clients extends Controller
{
  public function index()
  {
    $clients = User::where('role', 'user')
      ->orderBy('id', 'desc')
      ->get();

    return view('clients', [
      'clients' => $clients
    ]);
  }

  public function add($id)
  {
    $client = null;
    if ($id) {
      $client = User::where('role', 'user')->findOrFail($id);
    }

    return view('client-add', [
      'client' => $client,
    ]);
  }

  public function submit(Request $request)
  {
    $this->validator($request->all())->validate();

    $client = $this->create($request->all());
    event(new Registered($client));

    if (!$request->input('id')) {
      $message = 'Client successfully added';
    } else {
      $message = 'Client successfully updated';
    }

    return redirect()->back()->with('message', $message);
  }

  protected function validator(array $request)
  {
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
      'client_type.required' => 'Choose membership package',
    ];

    $rules = [
      'first_name' => ['required'],
      'last_name' => ['required'],
      'email' => ['required', 'email', 'unique:users,email' . (isset($request['id']) ? ',' . $request['id'] : '')],
      'phone' => ['required'],
      'company_name' => ['required'],
      'street_address' => ['required'],
      'city' => ['required'],
      'state' => ['required'],
      'zip_code' => ['required'],
      'client_type' => ['required'],
    ];

    return Validator::make($request, $rules, $messages);
  }

  protected function create(array $request)
  {
    $where = [
      'role' => 'user',
      'id' => $request['id'] ?? 0,
    ];
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
      'client_type' => $request['client_type'],
    ];
    return User::updateOrCreate($where, $update);
  }


  public function delete($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    if (!$authUser->isAdmin()) {
      return redirect()->back();
    }

    $client = User::where('role', 'user')->findOrFail($id);
    if (!$client) {
      return redirect()->back();
    }

    $client->delete();
    return redirect()->back()->with('message', 'Client deleted successfully');
  }

  public function details($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $client = User::where('role', 'user')->findOrFail($id);

    return view('client-details', [
      'client' => $client,
    ]);
  }

  public function requests($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $client = User::where('role', 'user')->findOrFail($id);
    $requestTypes = RequestType::get();
    $requests = ModelsRequest::orderBy('id', 'desc')
      ->where('user_id', $client->id)
      ->get();

    return view('client-requests', [
      'client' => $client,
      'requests' => $requests,
      'requestTypes' => $requestTypes,
    ]);
  }

  public function invoices($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $client = User::where('role', 'user')->findOrFail($id);
    $invoices = Invoice::orderBy('id', 'desc')
      ->where('user_id', $client->id)
      ->get();

    return view('client-invoices', [
      'client' => $client,
      'invoices' => $invoices,
    ]);
  }

  public function password($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $client = User::where('role', 'user')->findOrFail($id);

    return view('client-password', [
      'client' => $client,
    ]);
  }

  public function passwordSave(Request $request, $id)
  {
    $messages = [
      'new_password.required' => 'Enter a new password',
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

  protected function savePassword(array $request, $clientId)
  {
    $update = [
      'password' => Hash::make($request['new_password']),
    ];
    return User::where('id', $clientId)->update($update);
  }
}
