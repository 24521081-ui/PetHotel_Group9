@props([
    'data' => [],
    'xAxisKey' => 'name',
    'barConfigs' => [],
    'yAxisFormatter' => 'raw',
    'stacked' => false,
    'height' => '320px',
    'emptyText' => 'Không có dữ liệu',
])

@php
    $chartId = $attributes->get('id') ?: 'chart-bar-'.\Illuminate\Support\Str::uuid();
    $dataPayload = collect($data)->values()->all();
    $seriesPayload = collect($barConfigs)->values()->all();
    $isEmpty = count($dataPayload) === 0 || count($seriesPayload) === 0;
    $chartConfig = [
        'type' => 'bar',
        'data' => $dataPayload,
        'xAxisKey' => $xAxisKey,
        'series' => $seriesPayload,
        'formatter' => $yAxisFormatter,
        'stacked' => filter_var($stacked, FILTER_VALIDATE_BOOL),
    ];
@endphp

@include('components.chart.assets')

<div {{ $attributes->except('id')->merge(['class' => 'chart-component chart-component--bar']) }} style="--chart-height: {{ is_numeric($height) ? $height.'px' : $height }};">
    @if ($isEmpty)
        <div class="chart-component__empty">{{ $emptyText }}</div>
    @else
        <canvas id="{{ $chartId }}" class="chart-component__canvas" data-generic-chart></canvas>
        <script type="application/json" data-chart-config-for="{{ $chartId }}">
            {!! json_encode($chartConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
        </script>
    @endif
</div>
