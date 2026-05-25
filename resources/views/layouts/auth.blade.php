<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pet Hotel')</title>

    <link rel="stylesheet" href="{{ asset('assets/auth/css/auth.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('styles')
</head>
<body>

    @yield('content')

    <script src="{{ asset('assets/client/js/hooks/api-hooks.js') }}"></script>

    @stack('scripts')
</body>
</html>
