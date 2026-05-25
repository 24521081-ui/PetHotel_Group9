@extends('layouts.client')

@section('title', 'Chi tiết booking')

@section('content')

@php
$money = fn ($value) => number_format((float) $value, 0, ',', '.').'đ';
@endphp

<section class="account-page">
  <div class="account-container">

    <div class="booking-detail-head">
      <div>
        <h1>Booking #{{ $booking['id'] }}</h1>
        <p>{{ $booking['branch']['name'] }} · {{ $booking['date_range'] }}</p>
      </div>

      <span class="status-badge {{ $booking['status_class'] }}">{{ $booking['status_label'] }}</span>
    </div>

    @if (session('status'))
    <div class="alert alert-success">
      {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
      {{ $errors->first() }}
    </div>
    @endif

    <div class="booking-detail-grid">
      <section class="booking-detail-card">
        <h2>Thông tin lưu trú</h2>

        <div class="booking-detail-row">
          <span>Ngày nhận phòng</span>
          <strong>{{ $booking['checkin'] }}</strong>
        </div>

        <div class="booking-detail-row">
          <span>Ngày trả phòng</span>
          <strong>{{ $booking['checkout'] }}</strong>
        </div>

        <div class="booking-detail-row">
          <span>Số đêm</span>
          <strong>{{ $booking['nights'] }} đêm</strong>
        </div>

        <div class="booking-detail-row">
          <span>Tổng tiền</span>
          <strong>{{ $money($booking['total_amount']) }}</strong>
        </div>
      </section>

      <section class="booking-detail-card">
        <h2>Chi nhánh</h2>

        <div class="booking-detail-row">
          <span>Tên chi nhánh</span>
          <strong>{{ $booking['branch']['name'] }}</strong>
        </div>

        <div class="booking-detail-row">
          <span>Địa chỉ</span>
          <strong>{{ $booking['branch']['address'] }}</strong>
        </div>

        <div class="booking-detail-row">
          <span>Số điện thoại</span>
          <strong>{{ $booking['branch']['phone'] }}</strong>
        </div>
      </section>
    </div>

    <section class="booking-detail-card">
      <h2>Phòng lưu trú</h2>

      @forelse ($booking['rooms'] as $room)
      <div class="booking-detail-line">
        <span>{{ $room['type'] }}<small>Phòng {{ $room['room_number'] }}</small></span>
        <strong>{{ $money($room['price']) }}/đêm</strong>
      </div>
      @empty
      <p class="summary-empty">Chưa có thông tin phòng.</p>
      @endforelse
    </section>

    <section class="booking-detail-card">
      <h2>Thú cưng</h2>

      @forelse ($booking['pets'] as $pet)
      <div class="booking-detail-line">
        <span>{{ $pet['name'] }}<small>{{ $pet['species'] }} · {{ $pet['breed'] }}</small></span>
        <strong>{{ filled($pet['weight']) ? $pet['weight'].'kg' : 'Chưa cập nhật' }}</strong>
      </div>
      @empty
      <p class="summary-empty">Chưa có thông tin thú cưng.</p>
      @endforelse
    </section>

    <section class="booking-detail-card">
      <h2>Dịch vụ</h2>

      @forelse ($booking['services'] as $service)
      <div class="booking-detail-line">
        <span>{{ $service['name'] }}<small>{{ $service['pet_name'] }} · {{ $service['status'] }}</small></span>
        <strong>{{ $money($service['price']) }}</strong>
      </div>
      @empty
      <p class="summary-empty">Chưa chọn dịch vụ đi kèm.</p>
      @endforelse
    </section>

    @if (filled($booking['note']))
    <section class="booking-detail-card">
      <h2>Ghi chú</h2>
      <p class="booking-detail-note">{{ $booking['note'] }}</p>
    </section>
    @endif

    <div class="booking-detail-actions">
      <a href="{{ route('profile.history-booking.index') }}" class="detail-btn">Quay lại lịch sử</a>

      @if ($booking['show_payment'])
      <a href="{{ $booking['payment_url'] }}" class="pay-btn">Thanh toán</a>
      @endif
    </div>
  </div>
</section>

@endsection
