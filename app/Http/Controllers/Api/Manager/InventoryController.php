<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => BranchInventory::with(['branch', 'product'])
                ->orderBy('branch_id')
                ->get(),
        ]);
    }
}
