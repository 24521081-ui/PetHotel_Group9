<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use App\Models\TypeRoom;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => TypeRoom::where('is_active', 1)
                ->orderBy('base_price_per_day')
                ->get(),
        ]);
    }

    public function show(string $roomId): JsonResponse
    {
        return response()->json([
            'data' => TypeRoom::where('is_active', 1)
                ->where('type_room_id', $roomId)
                ->firstOrFail(),
        ]);
    }
}
