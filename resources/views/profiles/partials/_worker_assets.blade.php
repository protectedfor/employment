@section('js-assets')
    <script src="{{ asset('js/flow.min.js') }}"></script>
    <script src="{{ asset('js/init-flow.js') }}"></script>
@stop
@section('css-assets')
    <style>
        .hidden {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
@endsection