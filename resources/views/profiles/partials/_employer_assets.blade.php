@section('js-assets')
    <script src="{{ asset('js/flow.min.js') }}"></script>
    <script src="{{ asset('js/init-flow.js') }}"></script>
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    {{--<script> var editor = CKEDITOR.replace( 'overview',{ customConfig : 'config.js', } ); </script>--}}
@stop
@section('css-assets')
    <style>
        .hidden {
            display: none !important;
            visibility: hidden !important;
        }
    </style>
@endsection