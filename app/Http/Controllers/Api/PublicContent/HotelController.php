<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use App\Models\TypeRoom;
use Illuminate\Http\JsonResponse;

class HotelController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'areas' => $this->areas(),
                'room_types' => TypeRoom::where('is_active', 1)->get(),
            ],
        ]);
    }

    public function dogs(): JsonResponse
    {
        return $this->area('dogs');
    }

    public function cats(): JsonResponse
    {
        return $this->area('cats');
    }

    public function show(string $areaId): JsonResponse
    {
        return $this->area($areaId);
    }

    private function area(string $areaId): JsonResponse
    {
        $area = $this->areas()[$areaId] ?? null;

        abort_if(! $area, 404, 'Khong tim thay khu luu tru.');

        return response()->json([
            'data' => $area,
        ]);
    }

    private function areas(): array
    {
        return [
            'dogs' => [
                'id' => 'dogs',
                'name' => 'Khu cho cho',
                'species' => 'DOG',
            ],
            'cats' => [
                'id' => 'cats',
                'name' => 'Khu cho meo',
                'species' => 'CAT',
            ],
        ];
    }
}
