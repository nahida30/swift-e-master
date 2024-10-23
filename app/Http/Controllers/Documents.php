<?php

namespace App\Http\Controllers;

use App\Helpers\RequestType;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Request as ModelsRequest;

class Documents extends Controller
{
  public function index()
  {
    $authUser = Auth::user();
    $requestTypes = RequestType::get();
    $documents = ModelsRequest::where('user_id', $authUser->id)
      ->where('status', 'In-Process')
      ->orderBy('id', 'desc')
      ->get();
    return view('documents', [
      'documents' => $documents,
      'requestTypes' => $requestTypes,
    ]);
  }
}
