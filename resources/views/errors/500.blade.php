@extends('layouts.client')

@section('title', '500 - Loi he thong')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/client/css/error-500.css') }}">
@endpush

@section('content')
<section class="error-500-page">
    <div class="error-500-shell">
        <div class="error-500-copy">
            <span class="error-500-kicker">Pet Hotel support</span>
            <h1>500</h1>
            <h2>He thong dang tam nghi mot chut</h2>
            <p>
                Yeu cau cua ban chua duoc xu ly vi may chu dang gap su co. Doi chung toi kiem tra lai trong it phut,
                sau do ban co the thu lai thao tac vua roi.
            </p>

            <div class="error-500-actions">
                <a href="{{ url()->previous() === url()->current() ? route('home') : url()->previous() }}" class="error-500-primary">
                    <i class="fa-solid fa-rotate-left"></i>
                    Thu lai
                </a>

                <a href="{{ route('home') }}" class="error-500-secondary">
                    <i class="fa-solid fa-house"></i>
                    Ve trang chu
                </a>
            </div>
        </div>

        <div class="error-500-visual" aria-hidden="true">
            <div class="error-500-card">
                <img src="{{ asset('assets/client/images/right-home-500x554.png') }}" alt="">
                <div class="error-500-status">
                    <span></span>
                    Dang khoi dong lai dich vu
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
