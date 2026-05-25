@extends('layouts.client')

@section('title', 'Chọn chi nhánh đặt phòng')

@section('content')

<section class="booking-branch-select">
    <div class="booking-branch-panel">
        <div class="booking-branch-heading">
            <h1>Chọn chi nhánh</h1>
            <p>Chọn chi nhánh bạn muốn đặt phòng</p>
        </div>

        <div class="booking-branch-list branch-list">
            @forelse ($branches as $branch)
                <x-branch-card :branch="$branch" />
            @empty
                <div class="branch-empty">
                    <i class="fa-regular fa-map"></i>
                    <h3>Chưa có chi nhánh đang hoạt động</h3>
                    <p>Vui lòng quay lại sau hoặc liên hệ Pet Hotel để được hỗ trợ.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
