@props([
    'title' => '',
    'value' => '',
    'trend' => null,
    'isPositive' => true,
    'period' => 'tháng',
    'icon' => null,
])

@include('components.kpi_card', [
    'title' => $title,
    'value' => $value,
    'trend' => $trend,
    'isPositive' => $isPositive,
    'period' => $period,
    'icon' => $icon,
])
