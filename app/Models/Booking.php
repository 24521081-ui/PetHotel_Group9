<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::updated(function (Booking $booking): void {
            if (! $booking->wasChanged('status')) {
                return;
            }

            $status = strtoupper((string) $booking->status);

            if ($status === 'CHECKED_IN') {
                $booking->syncRoomStatus('IN_USE');

                return;
            }

            if (! in_array($status, ['CHECKED_OUT', 'COMPLETED', 'CANCELLED'], true)) {
                return;
            }

            $booking->syncRoomStatus('AVAILABLE');
        });
    }

    private function syncRoomStatus(string $status): void
    {
        $this->bookingRooms()
            ->with('room')
            ->get()
            ->each(function (BookingRoom $bookingRoom) use ($status): void {
                if (! $bookingRoom->room || $bookingRoom->room->status === 'MAINTENANCE') {
                    return;
                }

                if ($status === 'AVAILABLE' && $this->roomHasAnotherActiveOverlap($bookingRoom)) {
                    return;
                }

                $bookingRoom->room->update(['status' => $status]);
            });
    }

    private function roomHasAnotherActiveOverlap(BookingRoom $bookingRoom): bool
    {
        return BookingRoom::query()
            ->where('room_id', $bookingRoom->room_id)
            ->where('booking_id', '<>', $this->booking_id)
            ->whereHas('booking', function ($query): void {
                $query->whereIn('status', ['PENDING', 'CONFIRMED', 'CHECKED_IN'])
                    ->where('checkin_expected_at', '<', $this->checkout_expected_at)
                    ->where('checkout_expected_at', '>', $this->checkin_expected_at);
            })
            ->exists();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class, 'booking_id', 'booking_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_room', 'booking_id', 'room_id', 'booking_id', 'room_id')
            ->withPivot(['booking_room_id', 'assigned_at', 'notes']);
    }

    public function bookingServicePets()
    {
        return $this->hasMany(BookingServicePet::class, 'booking_id', 'booking_id');
    }

    public function bookingServicesPet()
    {
        return $this->bookingServicePets();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'booking_id', 'booking_id');
    }

    public function couponLogs()
    {
        return $this->hasMany(BookingCouponLog::class, 'booking_id', 'booking_id');
    }
}
