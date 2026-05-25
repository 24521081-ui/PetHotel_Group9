<?php

namespace App\Http\Controllers\Api\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Branch::withCount(['rooms', 'bookings', 'employees'])
                ->orderBy('branch_name')
                ->get(),
        ]);
    }
}
