@props([
    'title' => '',
    'message' => '',
    'type' => 'warning',
])

@include('components.alert', [
    'title' => $title,
    'message' => $message,
    'type' => $type,
])
