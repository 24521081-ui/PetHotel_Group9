<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Service::with('category')
                ->where('is_active', 1)
                ->orderBy('service_name')
                ->get(),
        ]);
    }

    public function spa(): JsonResponse
    {
        return $this->byKeyword('spa');
    }

    public function grooming(): JsonResponse
    {
        return $this->byKeyword('grooming');
    }

    public function show(string $serviceId): JsonResponse
    {
        return response()->json([
            'data' => Service::with(['category', 'products'])
                ->where('is_active', 1)
                ->where('service_id', $serviceId)
                ->firstOrFail(),
        ]);
    }

    private function byKeyword(string $keyword): JsonResponse
    {
        return response()->json([
            'data' => Service::with('category')
                ->where('is_active', 1)
                ->where(function ($query) use ($keyword): void {
                    $query->where('service_name', 'like', "%{$keyword}%")
                        ->orWhere('description_sv', 'like', "%{$keyword}%");
                })
                ->orderBy('service_name')
                ->get(),
        ]);
    }
}
