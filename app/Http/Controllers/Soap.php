<?php

namespace App\Http\Controllers;

use SoapFault;
use SoapClient;
use SoapHeader;
use Illuminate\Routing\Controller;

class Soap extends Controller
{
  public function index()
  {
    $wsdl = "https://erecord.mypalmbeachclerk.com/LandmarkErecord/MainModuleService.svc";

    $options = [
      'trace' => 1,
      'exceptions' => true,
      'cache_wsdl' => WSDL_CACHE_NONE
    ];

    $client = new SoapClient($wsdl, $options);

    // $headerbody = [
    //   'agentKey' => '#1528',
    //   'agentPassword' => '1'
    // ];
    // $header = new SoapHeader('http://tempuri.org/', 'AuthenticationHeader', $headerbody);
    // $client->__setSoapHeaders($header);

    $deed = storage_path('deed.tiff');
    $deedContents = file_get_contents($deed);
    if ($deedContents === false) {
      die('Failed to read the TIFF file.');
    }
    $deedBase64Encoded = base64_encode($deedContents);

    $priaPackage = '<pria:Package xmlns:pria="http://www.pria.org/eRecording">
        <pria:Document>
            <pria:DocumentType>Deed</pria:DocumentType>
            <pria:Grantor>
                <pria:Name>
                    <pria:FirstName>Surajit</pria:FirstName>
                    <pria:LastName>Pramanik</pria:LastName>
                </pria:Name>
            </pria:Grantor>
            <pria:Grantee>
                <pria:Name>
                    <pria:FirstName>Swifte</pria:FirstName>
                    <pria:LastName>Recording</pria:LastName>
                </pria:Name>
            </pria:Grantee>
            <pria:RecordingJurisdiction>
                <pria:County>USA</pria:County>
                <pria:State>FL</pria:State>
            </pria:RecordingJurisdiction>
            <pria:AttachedDocuments>
                <pria:DocumentFile>
                    <pria:FileName>deed.tiff</pria:FileName>
                    <pria:FileType>TIFF</pria:FileType>
                    <pria:FileContent>' . $deedBase64Encoded . '</pria:FileContent>
                </pria:DocumentFile>
            </pria:AttachedDocuments>
        </pria:Document>
    </pria:Package>';

    $params = [
      'agentKey' => '#1528',
      'agentPassword' => '1',
      'priaPackage' => $priaPackage,
    ];

    try {
      $response = $client->__soapCall('SubmitPackage', [$params]);
      return response()->json(['success' => $response]);
    } catch (SoapFault $e) {
      return response()->json(['error' => $e->faultcode . ' - ' . $e->faultstring]);
    }
  }
}
