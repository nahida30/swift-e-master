<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\County;
use App\Mail\NoteMail;
use App\Models\Activity;
use App\Helpers\Timezone;
use App\Models\RequestNote;
use App\Helpers\RequestType;
use App\Mail\NewRequestMail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use App\Models\Request as ModelsRequest;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use Symfony\Component\Console\Input\Input;

class Requests extends Controller
{
  private $clientEmail = 'mackendly@swifterecordingservices.com'; // 'jitdxpert@gmail.com';

  public function index(Request $request)
  {
    $authUser = Auth::user();
    $uid = $request->get('uid');
    $status = $request['status'];
    $requestTypes = RequestType::get();
    $requests = ModelsRequest::orderBy('id', 'desc');
    if ($status) {
      $requests->where('status', $status);
    }
    if (!$authUser->isAdmin()) {
      $requests->where('user_id', $authUser->id);
    }
    if ($uid) {
      $requests->where('user_id', $uid);
    }
    $requests = $requests->get();

    return view('requests', [
      'requests' => $requests,
      'requestTypes' => $requestTypes,
    ]);
  }

  public function add($id)
  {

    $counties = [];
    $request = null;
    $authUser = Auth::user();
    $requestTypes = RequestType::get();
    $states = County::select(['state'])->groupBy('state')->get();
    if ($id) {
      $request = ModelsRequest::findOrFail($id);
      if (!$authUser->isAdmin() && $request->status != 'Sent') {
        return redirect()->back();
      }
      $counties = County::where('state', $request->state)->get();
    }

    return view('request-add', [
      'states' => $states,
      'request' => $request,
      'counties' => $counties,
      'requestTypes' => $requestTypes,
    ]);
  }

  public function submit(Request $request)
  {
    $authUser = Auth::user();
    $this->validator($request->all(), $authUser)->validate();

    $newRequest = $this->create($request->all(), $authUser);
    if (!$authUser->isAdmin()) {
      Mail::to($authUser->email)->send(new NewRequestMail($newRequest->count, $newRequest->tag_it, $newRequest->doc_type));

      if (!$request->input('id')) {
        Activity::create([
          'type' => 'request',
          'user_id' => $authUser->id,
          'reference_id' => $newRequest->id,
          'description' => 'submitted a request',
        ]);
      } else {
        Activity::create([
          'type' => 'request',
          'user_id' => $authUser->id,
          'reference_id' => $newRequest->id,
          'description' => 'updated request',
        ]);
      }
    }
    event(new Registered($newRequest));

    if (!$request->input('id')) {
      $message = 'Request successfully added';
      return redirect()->route('requests.details', $newRequest->id);
    } else {
      $message = 'Request successfully updated';
      return redirect()->route('requests.add', $newRequest->id)->with('message', $message);
    }
  }

  protected function validator(array $request, $authUser)
  {
    $messages = [
      'doc_type.required' => 'Please choose document type',
      'tag_it.required' => 'Please enter a tag',
      'state.required' => 'Please enter your state',
      'county.required' => 'Please enter your county',
      'fileurl.required' => 'Please upload your file',
      'status.required' => 'Please choose status',
    ];

    $rules = [
      'doc_type' => ['required'],
      'tag_it' => ['required'],
      'state' => ['required'],
      'county' => ['required'],
    ];

    if (!isset($request['id'])) {
      $rules['fileurl'] = ['required'];
    }

    if ($authUser->isAdmin()) {
      $rules['status'] = ['required'];
    }

    return Validator::make($request, $rules, $messages);
  }

  protected function create(array $request, $authUser)
  {
    if (!isset($request['id'])) {
      $authRequestCount = ModelsRequest::where('user_id', $authUser->id)->count();
      $authRequestCount = $authRequestCount + 1;
    } else {
      $oldRequest = ModelsRequest::findOrFail($request['id']);
      $authRequestCount = $oldRequest->count ?? 0;
    }
    $where = [
      'id' => $request['id'] ?? 0,
    ];
    if (!$request['uploaded_at']) {
      $request['uploaded_at'] = date('Y-m-d H:i:s');
    }
    $update = [
      'count' => $authRequestCount,
      'doc_type' => $request['doc_type'],
      'tag_it' => $request['tag_it'],
      'state' => $request['state'],
      'county' => $request['county'],
      'file' => $request['fileurl'],
      'file_name' => $request['file_name'],
      'uploaded_at' => $request['uploaded_at'],
      'original_name' => $request['original_name'],
    ];
    if (!$authUser->isAdmin()) {
      $update['user_id'] = $authUser->id;
    } else {
      $update['status'] = $request['status'];
    }
    return ModelsRequest::updateOrCreate($where, $update);
  }

  public function delete($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    $request = ModelsRequest::findOrFail($id);
    if (!$request) {
      return redirect()->back();
    }
    if ($authUser->role == 'user') {
      if ($request->user_id != $authUser->id) {
        return redirect()->back();
      }
      if ($request->status != 'Sent') {
        return redirect()->back()->with('message', 'Unable to cancel In-Process Request');
      }
    }

    try {
      $fileName = $request->file_name;
      $containerName = env('AZURE_STORAGE_CONTAINER');
      $connectionString = env('AZURE_STORAGE_CONNECTION_STRING');
      $blobClient = BlobRestProxy::createBlobService($connectionString);
      $blobClient->deleteBlob($containerName, $fileName);
    } catch (\Exception $e) {
      // 
    }

    $request->delete();

    if (!$authUser->isAdmin()) {
      Activity::create([
        'type' => 'request',
        'reference_id' => $id,
        'user_id' => $authUser->id,
        'description' => 'cancelled request',
      ]);
    }

    return redirect()->back()->with('message', 'Request successfully cancelled');
  }

  public function notes($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    $request = ModelsRequest::with('notes')->findOrFail($id);
    if ($authUser->role == 'user' && $authUser->id != $request->user_id) {
      return redirect()->back();
    }

    RequestNote::where('status', 'unread')
      ->where('receiver_id', $authUser->id)
      ->where('request_id', $id)
      ->update(['status' => 'read']);

    return view('requests-notes', [
      'request' => $request,
    ]);
  }

  public function accept($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    if ($authUser->role == 'user') {
      return redirect()->back();
    }

    $request = ModelsRequest::with('notes')->findOrFail($id);
    if ($request) {
      $request->status = 'In-Process';
      $request->save();
    }

    return redirect()->back()->with('message', 'Request successfully accepted');
  }

  public function details($id)
  {
    if (!$id) {
      return redirect()->back();
    }

    $authUser = Auth::user();
    $request = ModelsRequest::findOrFail($id);


    if ($authUser->role == 'user' && $authUser->id != $request->user_id) {
      return redirect()->back();
    }

    $requestTypes = RequestType::get();

    return view('requests-details', [
      'request' => $request,
      'requestTypes' => $requestTypes,
    ]);
  }


  // AJAX Functions
  public function getCounties($state)
  {
    if (!$state) {
      return response()->json([]);
    }

    $counties = County::where('state', $state)->get();
    return response()->json($counties);
  }

  public function uploadFile(Request $request)
  {
    try {
      $request->validate([
        'file' => 'required|mimes:pdf|max:24576', // max 24MB
      ], [
        'file.required' => 'Please upload your file',
        'file.mimes' => 'Only PDF files are allowed to upload',
        'file.max' => 'The file size must not exceed 24MB'
      ]);

      if ($request->file('file')) {
        $tiffFiles = [];
        $authUser = Auth::user();
        $now = date('Y-m-d H:i:s');
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileContents = file_get_contents($filePath);
        $orgName = $file->getClientOriginalName();
        $fileName = time() . '-' . $orgName;
        $fileInfo = pathinfo($fileName);
        $onlyFileName = $fileInfo['filename'];
        $tiffFileName = $onlyFileName . '_%03d.tiff';

        // Create Blob Client
        $containerName = env('AZURE_STORAGE_CONTAINER');
        $connectionStr = env('AZURE_STORAGE_CONNECTION_STRING');
        $blobClient = BlobRestProxy::createBlobService($connectionStr);

        // Upload PDF to Azure Storage
        $encryptedContents = Crypt::encrypt($fileContents);
        $blobClient->createBlockBlob($containerName, $fileName, $encryptedContents);

        if (!$request->input('admin_request_id')) {
          // Store Temporary PDF to Server
          $pdfFilePath = public_path('uploads/' . $fileName);
          file_put_contents($pdfFilePath, $fileContents);

          // Store Temporary TIFF to Server
          $tiffFilePath = public_path('uploads/' . $tiffFileName);
          $command = "gs -sDEVICE=tiff24nc -r300 -dNOPAUSE -dBATCH -dUseCropBox -sCompression=lzw -sOutputFile=$tiffFilePath $pdfFilePath";
          shell_exec($command);

          // Upload TIFF to Azure Storage
          $options = new CreateBlockBlobOptions();
          $options->setContentType('image/tiff');
          $tiffFiles = glob(public_path('uploads/' . $onlyFileName . '_*.tiff'));
          foreach ($tiffFiles as $i => $tiffFile) {
            $tiffName = basename($tiffFile);
            $tiffContent = fopen($tiffFile, 'r');
            $blobClient->createBlockBlob($containerName, $tiffName, $tiffContent, $options);
            // Unlink Temporary TIFF
            unlink($tiffFile);
          }

          // Unlink Temporary PDF
          unlink($pdfFilePath);
        }

        $fileUrl = url('/getfile/' . $fileName);

        if ($request->input('request_id')) {
          $requestId = $request->input('request_id');
          $req = ModelsRequest::findOrFail($requestId);
          if ($req) {
            $req->file2 = $fileUrl;
            $req->uploaded_at2 = $now;
            $req->file_name2 = $fileName;
            $req->original_name2 = $orgName;
            $req->tiff_pages2 = count($tiffFiles);
            $req->save();
          }
        }

        if ($request->input('admin_request_id')) {
          $requestId = $request->input('admin_request_id');
          $req = ModelsRequest::findOrFail($requestId);
          if ($req) {
            $req->completed_at = $now;
            $req->completed_file = $fileUrl;
            $req->completed_file_name = $fileName;
            $req->completed_original_name = $orgName;
            $req->save();
          }
        }

        return response()->json([
          'file' => $fileUrl,
          'type' => 'success',
          'uploaded_at' => $now,
          'file_name' => $fileName,
          'original_name' => $orgName,
          'tiff_pages' => count($tiffFiles),
          'text' => 'File uploaded successfully',
          'uploaded_at_local' => Timezone::toLocal($now, $authUser->timezone_offset),
        ]);
      }
    } catch (ValidationException $e) {
      return response()->json(['errors' => $e->errors()], 422);
    }

    return response()->json([
      'type' => 'error',
      'text' => 'File upload failed. Please check the file and try again',
    ], 400);
  }

  public function deleteFile(Request $request)
  {
    $request->validate([
      'file' => 'required|string',
    ]);

    try {
      $fileName = $request->input('file');
      $tiffPages = $request->input('tiff_pages');
      $fileInfo = pathinfo($fileName);
      $onlyFileName = $fileInfo['filename'];

      $containerName = env('AZURE_STORAGE_CONTAINER');
      $connectionString = env('AZURE_STORAGE_CONNECTION_STRING');
      $blobClient = BlobRestProxy::createBlobService($connectionString);
      $blobClient->deleteBlob($containerName, $fileName);

      if (!$request->input('admin_request_id')) {
        for ($i = 1; $i <= $tiffPages; $i++) {
          $tiffFile = $onlyFileName . sprintf("_%03d", $i) . '.tiff';
          $blobClient->deleteBlob($containerName, $tiffFile);
        }
      }
    } catch (\Exception $e) {
      // 
    }

    if ($request->input('request_id')) {
      $requestId = $request->input('request_id');
      $req = ModelsRequest::findOrFail($requestId);
      if ($req) {
        $req->file2 = null;
        $req->file_name2 = null;
        $req->uploaded_at2 = null;
        $req->original_name2 = null;
        $req->tiff_pages2 = null;
        $req->save();
      }
    }

    if ($request->input('admin_request_id')) {
      $requestId = $request->input('admin_request_id');
      $req = ModelsRequest::findOrFail($requestId);
      if ($req) {
        $req->completed_at = null;
        $req->completed_file = null;
        $req->completed_file_name = null;
        $req->completed_original_name = null;
        $req->save();
      }
    }

    return response()->json([
      'type' => 'success',
      'text' => 'File deleted successfully',
    ]);
  }

  public function readNote(Request $request)
  {
    $authUser = Auth::user();
    $requestId = $request->input('request_id');

    RequestNote::where('status', 'unread')
      ->where('receiver_id', $authUser->id)
      ->where('request_id', $requestId)
      ->update(['status' => 'read']);

    $notes = RequestNote::where('status', 'unread')
      ->where('receiver_id', $authUser->id)
      ->where('status', 'unread')
      ->get();
    $html = '';
    foreach ($notes as $i => $note) {
      $html .= '<li class="message-item position-relative">';
      if ($note->issue) {
        $html .= '<span class="position-absolute text-danger" style="right:1rem" data-toggle="tooltip" data-placement="top" title="Issue">';
        $html .= '<i class="bi bi-info-circle"></i>';
        $html .= '</span>';
      }
      $html .= '<a href="' . route('requests.details', !$note->issue ? [$note->request_id, 'note' => $note->id] : [$note->request_id, 'note' => $note->id, 'issue' => true]) . '">';
      $html .= '<img src="' . asset('img/profile-img.png') . '" alt="" class="rounded-circle" />';
      $html .= '<div>';
      $html .= '<h4>' . $note->sender->name . '</h4>';
      $html .= '<p>' . substr($note->note, 0, 100) . '</p>';
      $html .= '<p>' . $note->created_at_local . '</p>';
      $html .= '</div>';
      $html .= '</a>';
      $html .= '</li>';
      if ($i < count($notes) - 1) {
        $html .= '<li><hr class="dropdown-divider" /></li>';
      }
    }
    return response()->json([
      'notes_html' => $html,
      'count' => count($notes),
    ]);
  }

  public function saveNote(Request $request)
  {
    try {
      $request->validate([
        'note' => 'required|string',
        'sender_id' => 'nullable|exists:users,id',
        'request_id' => 'required|exists:requests,id',
      ], [
        'note.required' => 'Please write your note',
      ]);

      $authUser = Auth::user();
      $senderId = $request->input('sender_id');
      $requestId = $request->input('request_id');
      $req = ModelsRequest::findOrFail($requestId);
      $admin = User::where('role', 'admin')->first();
      if ($senderId == $admin->id) {
        $receiverId = $req->user_id;
      } else {
        $receiverId = $admin->id;
      }
      $params = $request->all();
      $params['status'] = 'unread';
      $params['receiver_id'] = $receiverId;
      $params['issue'] = json_decode($params['issue']);

      $note = RequestNote::create($params);

      if (!$authUser->isAdmin()) {
        Activity::create([
          'type' => 'note',
          'user_id' => $authUser->id,
          'reference_id' => $note->id,
          'description' => 'added swift-e note',
        ]);
      }

      if ($senderId != $admin->id) {
        Mail::to($this->clientEmail)->send(new NoteMail($request->input('note'), $req->tag_it, $req->count));
      }

      return response()->json([
        'note' => $note,
      ]);
    } catch (ValidationException $e) {
      return response()->json(['errors' => $e->errors()], 422);
    }
  }
}
