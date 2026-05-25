<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'featured_services' => Service::with('category')
                    ->where('is_active', 1)
                    ->limit(6)
                    ->get(),
                'featured_branches' => Branch::where('is_active', 1)
                    ->limit(6)
                    ->get(),
            ],
        ]);
    }
}
