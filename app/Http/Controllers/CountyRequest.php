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
use Illuminate\Support\Facades\Http;

class CountyRequest extends Controller
{
 public function VerifyRequest($id){

  $parser = new \Smalot\PdfParser\Parser();
  $pdf = $parser->parseFile(public_path('/document/document.pdf'));

  $text = $pdf->getText();

    $authUser = Auth::user();
    $request = ModelsRequest::findOrFail($id);
   

    if ($authUser->role == 'user' && $authUser->id != $request->user_id) {
      return redirect()->back();
    }



    ## if you check the data plz uncomment this code

    // $arr = [

    //   "country"=>$request->county,
    //   "state"=>$request->state,
    //   "payment_status"=>$request->payment_status,
    //   'status'=>$request->status,
    //   'status'=>$request->status,
    //   'rawfile'=>$request->file,
    //   'fileocr'=>$text

    // ];

    // return $arr

     ## if you check the data plz uncomment this code

    $siteDomain = 'https://xyz.com'

    $postData = Http::post($siteDomain.'/api/county',[
      "country"=>$request->county,
      "state"=>$request->state,
      "payment_status"=>$request->payment_status,
      'status'=>$request->status,
      'status'=>$request->status,
      'rawfile'=>$request->file,
      'fileocr'=>$text
        ])->json();

    return $postData;

    // return response()->json(['data'=>$arr],200);

 }
}
