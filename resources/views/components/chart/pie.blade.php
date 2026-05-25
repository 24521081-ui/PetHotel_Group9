@props([
    'data' => [],
    'nameKey' => 'name',
    'dataKey' => 'value',
    'colors' => ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
    'tooltipFormatter' => 'raw',
    'height' => '320px',
    'emptyText' => 'Không có dữ liệu',
])

@php
    $chartId = $attributes->get('id') ?: 'chart-pie-'.\Illuminate\Support\Str::uuid();
    $dataPayload = collect($data)->values()->all();
    $colorsPayload = collect($colors)->values()->all();
    $isEmpty = count($dataPayload) === 0;
    $chartConfig = [
        'type' => 'pie',
        'data' => $dataPayload,
        'nameKey' => $nameKey,
        'dataKey' => $dataKey,
        'colors' => $colorsPayload,
        'formatter' => $tooltipFormatter,
    ];
@endphp

@include('components.chart.assets')

<div {{ $attributes->except('id')->merge(['class' => 'chart-component chart-component--pie']) }} style="--chart-height: {{ is_numeric($height) ? $height.'px' : $height }};">
    @if ($isEmpty)
        <div class="chart-component__empty">{{ $emptyText }}</div>
    @else
        <canvas id="{{ $chartId }}" class="chart-component__canvas" data-generic-chart></canvas>
        <script type="application/json" data-chart-config-for="{{ $chartId }}">
            {!! json_encode($chartConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
        </script>
    @endif
</div>
