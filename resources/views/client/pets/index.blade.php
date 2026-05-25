@extends('layouts.client')

@section('title', 'Thú cưng của tôi')

@section('content')

<section class="account-page">
  <div class="account-container">

    <div class="account-header">
      <div>
        <h1>Thú cưng của tôi</h1>
        <p class="account-subtitle">{{ $pets->count() }} thú cưng đang được lưu trong hồ sơ</p>
      </div>
    </div>

    @if ($errors->any())
      <div class="pet-page-alert pet-page-alert--error">
        {{ $errors->first() }}
      </div>
    @endif

    @if (session('status'))
      <div class="pet-page-alert pet-page-alert--success">
        {{ session('status') }}
      </div>
    @endif

    @include('client.pets._list', ['pets' => $pets])

  </div>
</section>

@endsection
