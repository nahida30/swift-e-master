<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LandmarkService;
use Illuminate\Routing\Controller;

class Pria extends Controller
{
    protected $landmarkService;

    public function __construct(LandmarkService $landmarkService)
    {
        $this->landmarkService = $landmarkService;
    }

    public function submitPackage(Request $request)
    {
        $priaPackage = [
            'Document' => $request->input('document'),
            'Metadata' => $request->input('metadata'),
        ];

        $response = $this->landmarkService->submitPriaPackage($priaPackage);

        return response()->json($response);
    }
}
