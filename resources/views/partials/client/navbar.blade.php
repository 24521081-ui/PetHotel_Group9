@php
$navbarUser = auth()->user();
$navbarProfile = $navbarUser?->customer ?: $navbarUser?->employee;
$navbarName = trim((string) ($navbarProfile?->full_name ?: $navbarUser?->name ?: 'Khach hang'));
$navbarEmail = $navbarUser?->email ?: 'Chua co email';
$navbarInitials = collect(preg_split('/\s+/', $navbarName, -1, PREG_SPLIT_NO_EMPTY))
->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
->take(2)
->implode('') ?: 'KH';

$mainLinks = [
['label' => 'Trang chủ', 'url' => url('/'), 'active' => request()->is('/')],
['label' => 'Hệ thống chi nhánh', 'url' => url('/branches'), 'active' => request()->is('branches*')],
['label' => 'Booking', 'url' => url('/booking'), 'active' => request()->is('booking*')],
];

$serviceLinks = [
['label' => 'Grooming', 'url' => url('/services/grooming')],
];

$roomLinks = [
[
'label' => 'Khách sạn thú cưng cho Chó',
'url' => url('/rooms/dog'),
],
[
'label' => 'Khách sạn thú cưng cho Mèo',
'url' => url('/rooms/cat'),
],
];

$accountLinks = [
['label' => 'Hồ sơ cá nhân', 'url' => url('/profile'), 'icon' => 'fa-regular fa-user'],
['label' => 'Thú cưng của tôi', 'url' => url('/pets'), 'icon' => 'fa-regular fa-circle-dot'],
['label' => 'Lịch sử đặt phòng', 'url' => route('profile.history-booking.index'), 'icon' => 'fa-regular fa-calendar'],
];
@endphp

<nav class="navbar" aria-label="Điều hướng chính">
  <div class="nav-brand">
    <a href="{{ url('/') }}" class="nav-brand-link">
      <img src="{{ asset('assets/client/images/logo&banner/logo.jpg') }}" alt="Pet Hotel Logo" class="nav-brand-logo">
      <span>Pet Hotel</span>
    </a>
  </div>

  <div class="nav-links">
    <a href="{{ $mainLinks[0]['url'] }}" class="nav-link {{ $mainLinks[0]['active'] ? 'active' : '' }}">
      {{ $mainLinks[0]['label'] }}
    </a>

    <div class="nav-dropdown nav-services-wrap">
      <button type="button" class="nav-link nav-dropdown-toggle {{ request()->is('services*') ? 'active' : '' }}"
        aria-expanded="false">
        Dịch vụ
        <i class="fa-solid fa-chevron-down"></i>
      </button>

      <div class="nav-dropdown-menu">
        @foreach ($serviceLinks as $serviceLink)
        <a href="{{ $serviceLink['url'] }}" class="nav-dropdown-link">
          {{ $serviceLink['label'] }}
        </a>
        @endforeach
      </div>
    </div>

    <div class="nav-hotel-wrap" id="nav-hotel-wrap">
      <button type="button" class="nav-link nav-dropdown-toggle {{ request()->is('rooms*') ? 'active' : '' }}"
        id="nav-hotel-btn" aria-expanded="false" aria-controls="nav-hotel-dropdown">
        Khách sạn thú cưng
        <i class="fa-solid fa-chevron-down"></i>
      </button>

      <div class="nav-hotel-dropdown" id="nav-hotel-dropdown">
        @foreach ($roomLinks as $roomLink)
        <a href="{{ $roomLink['url'] }}" class="nav-hotel-dd-item">
          <span class="nav-hotel-copy">
            <strong>{{ $roomLink['label'] }}</strong>
          </span>
        </a>
        @endforeach
      </div>
    </div>

    @foreach (array_slice($mainLinks, 1) as $link)
    <a href="{{ $link['url'] }}" class="nav-link {{ $link['active'] ? 'active' : '' }}">
      {{ $link['label'] }}
    </a>
    @endforeach
  </div>

  <div class="nav-right">
    @guest
    <div class="nav-guest" id="nav-guest">
      <a href="{{ route('login') }}" class="btn-ghost btn-sm">Đăng nhập</a>
      <a href="{{ route('register') }}" class="btn-orange btn-sm">Đăng kí</a>
    </div>
    @endguest

    @auth
    <div class="nav-user" id="nav-user">
      <button type="button" class="avatar-btn" id="avt-btn" aria-expanded="false" aria-controls="dd">
        <span class="avatar" id="nav-avatar-initials">{{ $navbarInitials }}</span>
        <span class="avatar-name" id="nav-avatar-name">{{ $navbarName }}</span>
        <i class="fa-solid fa-chevron-down"></i>
      </button>

      <div class="nav-account-dropdown" id="dd">
        <div class="nav-account-meta">
          <strong>{{ $navbarName }}</strong>
          <span>{{ $navbarEmail }}</span>
        </div>

        <div class="dd-divider"></div>

        @foreach ($accountLinks as $accountLink)
        <a href="{{ $accountLink['url'] }}" class="dd-item">
          <i class="{{ $accountLink['icon'] }}"></i>
          {{ $accountLink['label'] }}
        </a>
        @endforeach

        <div class="dd-divider"></div>

        <form method="POST" action="{{ route('authentication.logout') }}" class="nav-logout-form">
          @csrf
          <button type="submit" class="dd-item danger">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            Đăng xuất
          </button>
        </form>
      </div>
    </div>
    @endauth
  </div>
</nav>
