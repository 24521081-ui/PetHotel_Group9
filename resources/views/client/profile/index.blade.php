@extends('layouts.client')

@section('title', 'Hồ sơ cá nhân')

@section('content')

@php
$display = fn ($value) => filled($value) ? $value : 'Chưa cập nhật';
$birthday = $profile['birthday'] ?? null;
$birthdayText = $birthday
? \Illuminate\Support\Carbon::parse($birthday)->format('d/m/Y')
: null;
@endphp

<section class="account-page">
  <div class="account-container">

    <div class="account-header">
      <h1>Hồ sơ cá nhân</h1>

      <a href="{{ url('/profile/edit') }}" class="light-btn">
        <i class="fa-regular fa-pen-to-square"></i>
        Chỉnh sửa
      </a>
    </div>

    <div class="profile-card">
      <div class="profile-top">
        <div class="profile-avatar">{{ $profile['avatar_text'] }}</div>

        <div class="profile-name">
          <h2>{{ $display($profile['full_name'] ?? null) }}</h2>
          <p>{{ $display($profile['email'] ?? null) }}</p>
        </div>
      </div>

      <div class="profile-grid">
        <div class="profile-info-item">
          <span>Số điện thoại</span>
          <strong>{{ $display($profile['phone'] ?? null) }}</strong>
        </div>

        <div class="profile-info-item">
          <span>Ngày sinh</span>
          <strong>{{ $display($birthdayText) }}</strong>
        </div>

        <div class="profile-info-item">
          <span>Địa chỉ</span>
          <strong>{{ $display($profile['address'] ?? null) }}</strong>
        </div>

        <div class="profile-info-item">
          <span>Thành viên từ</span>
          <strong>{{ $display($profile['member_since'] ?? null) }}</strong>
        </div>
      </div>
    </div>

  </div>
</section>

@endsection