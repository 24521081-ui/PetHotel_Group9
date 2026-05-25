<?php

namespace App\Http\Controllers\Api\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;

class FinanceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'paid_revenue' => Payment::where('status', 'SUCCESS')->sum('amount'),
                'pending_orders' => Order::where('status', 'PENDING')->count(),
                'paid_orders' => Order::where('status', 'PAID')->count(),
            ],
        ]);
    }
}
