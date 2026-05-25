<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PolicyController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => array_values($this->policies()),
        ]);
    }

    public function show(string $policyId): JsonResponse
    {
        $policy = $this->policies()[$policyId] ?? null;

        abort_if(! $policy, 404, 'Khong tim thay chinh sach.');

        return response()->json([
            'data' => $policy,
        ]);
    }

    private function policies(): array
    {
        return [
            'booking' => [
                'id' => 'booking',
                'title' => 'Chinh sach dat lich',
                'description' => 'Quy dinh ve dat lich, doi lich va huy lich dich vu.',
            ],
            'payment' => [
                'id' => 'payment',
                'title' => 'Chinh sach thanh toan',
                'description' => 'Quy dinh ve dat coc, thanh toan va hoan tien.',
            ],
            'pet-care' => [
                'id' => 'pet-care',
                'title' => 'Chinh sach cham soc thu cung',
                'description' => 'Quy dinh ve suc khoe, tiem chung va an toan cua thu cung.',
            ],
        ];
    }
}
