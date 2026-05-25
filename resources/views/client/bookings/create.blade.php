@extends('layouts.client')

@section('title', 'Đặt phòng')

@section('content')

@php
    $branch = $bookingData['branch'];
    $roomTypes = $bookingData['roomTypes'];
    $pets = $bookingData['pets'];
@endphp

<section class="booking-page-v2" id="booking-app" data-booking='@json($bookingData)'>
    @if ($errors->any())
        <div class="booking-alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="booking-wrapper">
        @include('client.bookings.partials.selection', [
            'branch' => $branch,
            'roomTypes' => $roomTypes,
            'pets' => $pets,
        ])

        @include('client.bookings.partials.summary', [
            'branch' => $branch,
        ])
    </div>

    @include('client.bookings.partials.modals')
</section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/client/js/booking.js') }}"></script>
@endpush
