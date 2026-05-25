@props([
    'branch',
])

@php
    $image = $branch['image'] ?? $branch['image_url'] ?? asset('assets/client/images/right-home-500x554.png');
    $name = $branch['name'] ?? $branch['branch_name'] ?? 'Chi nhánh đang cập nhật';
    $district = $branch['district'] ?? 'Khu vực khác';
    $address = $branch['address'] ?? 'Đang cập nhật';
    $phone = $branch['phone'] ?? 'Đang cập nhật';
    $hours = $branch['hours'] ?? trim(($branch['open_time'] ?? '').' - '.($branch['close_time'] ?? ''), ' -');
    $hours = $hours ?: 'Đang cập nhật';
    $rating = $branch['rating'] ?? '4.5';
    $reviews = $branch['reviews'] ?? $branch['review_count'] ?? 0;
    $bookingUrl = $branch['bookingUrl'] ?? $branch['booking_url'] ?? url('/booking/branch/'.($branch['id'] ?? $branch['branch_id']));
    $detailUrl = $branch['detailUrl'] ?? $branch['detail_url'] ?? route('branches.show', $branch['id'] ?? $branch['branch_id']);
@endphp

<article {{ $attributes->class('branch-card') }}>
    <img src="{{ $image }}" alt="{{ $name }}">

    <div class="branch-info">
        <div class="branch-card-heading">
            <h3>{{ $name }}</h3>
            <span>{{ $district }}</span>
        </div>

        <p><i class="fa-solid fa-location-dot"></i> {{ $address }}</p>
        <p><i class="fa-solid fa-phone"></i> {{ $phone }}</p>
        <p><i class="fa-regular fa-clock"></i> {{ $hours }}</p>
        <p class="rating">
            <i class="fa-solid fa-star"></i> {{ $rating }}
            <span>({{ $reviews }} đánh giá)</span>
        </p>
    </div>

    <div class="branch-actions">
        <a href="{{ $bookingUrl }}" class="branch-booking-btn">Đặt phòng</a>
        <a href="{{ $detailUrl }}" class="branch-detail-btn">Xem chi tiết</a>
    </div>
</article>
