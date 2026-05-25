<?php

namespace App\Http\Controllers\Api\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Service::with('category')
                ->orderBy('service_name')
                ->get(),
        ]);
    }
}
