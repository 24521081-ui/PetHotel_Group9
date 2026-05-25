@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/client/css/components/chart.css') }}">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script src="{{ asset('assets/client/js/components/chart-components.js') }}"></script>
    @endpush
@endonce
