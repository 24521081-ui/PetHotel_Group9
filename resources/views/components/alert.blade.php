@props([
    'title' => '',
    'message' => '',
    'type' => 'warning',
])

@php
    $alertType = in_array($type, ['warning', 'danger'], true) ? $type : 'warning';
    $icon = $alertType === 'danger' ? '🚨' : '⚠️';
@endphp

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/client/css/components/alert-box.css') }}">
    @endpush
@endonce

<div {{ $attributes->merge(['class' => 'alert-box-wrapper']) }}>
    <div class="alert-box alert-box--{{ $alertType }}">
        <h4 class="alert-box__header">
            <span class="alert-box__icon" aria-hidden="true">{{ $icon }}</span>
            {{ $title }}
        </h4>

        <p class="alert-box__message">{{ $message }}</p>
    </div>
</div>
