<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;

class PDF extends Controller
{
  public function converter()
  {
    $uploadsPath = public_path('uploads');
    $pdfFile = $uploadsPath . '/1717981739-CE.pdf';
    $tiffFile = $uploadsPath . '/1717981739-CE_%03d.tiff';
    // $command = "gs -sDEVICE=tiff24nc -sOutputFile=$tiffFile -dBATCH -dNOPAUSE $pdfFile";
    // $command = "gs -dNOPAUSE -dBATCH -sDEVICE=tiff24nc -r300 -sOutputFile=$tiffFile $pdfFile";
    // $command = "gs -sDEVICE=tiff24nc -r300 -dNOPAUSE -dBATCH -dUseCropBox -sCompression=lzw -sOutputFile=$tiffFile $pdfFile";
    $command = "gs -sDEVICE=tiff24nc -r300 -dNOPAUSE -dBATCH -dUseCropBox -sCompression=lzw -sOutputFile=$tiffFile $pdfFile";
    shell_exec($command);

    return view('pdfconverter');
  }

  public function viewer()
  {

    return view('pdfviewer');
  }


  public function getFile($fileName)
  {
    $containerName = env('AZURE_STORAGE_CONTAINER');
    $blobClient = BlobRestProxy::createBlobService(env('AZURE_STORAGE_CONNECTION_STRING'));
    $getBlobResult = $blobClient->getBlob($containerName, $fileName);
    $blobProperties = ($getBlobResult->getProperties());
    $fileSize = $blobProperties->getContentLength();

    if ($fileSize) {
      $encryptedContents = stream_get_contents($getBlobResult->getContentStream());
      $decryptedContents = Crypt::decrypt($encryptedContents);

      return response($decryptedContents)
        ->header('Content-Type', 'application/octet-stream')
        ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }

    return response()->json(['message' => 'File not found!'], 404);
  }
}
