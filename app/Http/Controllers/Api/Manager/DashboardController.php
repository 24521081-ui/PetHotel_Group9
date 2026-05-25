<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'bookings' => Booking::count(),
                'orders' => Order::count(),
                'available_rooms' => Room::where('status', 'AVAILABLE')->count(),
            ],
        ]);
    }
}
