<?php

namespace App\Services;

use SoapClient;

class LandmarkService
{
    protected $client;

    public function __construct()
    {
        $this->client = new SoapClient('https://erecordqa.mypalmbeachclerk.com/MainModuleService.svc?wsdl', [
            'login'    => '#1528',
            'password' => '1',
            'trace'    => true,
            'exceptions' => true,
        ]);
    }

    public function submitPriaPackage(array $priaPackage)
    {
        try {
            $response = $this->client->__soapCall('SubmitPackage', [$priaPackage]);

            if ($response->IsSuccess) {
                return [
                    'status' => 'success',
                    'message' => 'Package submitted successfully',
                    'response' => $response
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to submit package',
                    'details' => $response->ErrorMessage
                ];
            }
        } catch (\SoapFault $e) {
            return [
                'status' => 'error',
                'message' => 'SOAP error: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'General error: ' . $e->getMessage()
            ];
        }
    }
}
