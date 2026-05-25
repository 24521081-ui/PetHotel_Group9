<?php

namespace App\Http\Controllers\Api\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'message' => 'Danh sach doi tac/nha cung cap se duoc gan voi module supplier sau.',
                'products' => Product::orderBy('product_name')->limit(20)->get(),
            ],
        ]);
    }
}
