<?php

namespace App\Http\Controllers\Api\Ceo;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'branches' => Branch::count(),
                'services' => Service::count(),
                'bookings' => Booking::count(),
                'orders' => Order::count(),
            ],
        ]);
    }
}
