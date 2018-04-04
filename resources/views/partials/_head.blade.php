<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($metatitle) && $metatitle ? $metatitle : config('admin.title') }}</title>
	<meta name="description" content="{{ isset($metadesc) ? $metadesc : null }}">
	<meta name="keywords" content="{{ isset($metakeyw) ? $metakeyw : null }}">
	@if(Request::url() == route('resumes.show', Request::route('id')))
		<meta name="robots" content="noindex">
	@endif

    <!-- Bootstrap -->
    <link rel="shortcut icon" href="/favicon.png"/>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/fonts.css">
	<link rel="stylesheet" href="{{ asset('libs/alertifyjs/css/alertify.min.css') }}">
	<link rel="stylesheet" href="{{ asset('libs/alertifyjs/css/themes/bootstrap.min.css') }}">
    @yield('css-assets')
    <link rel="stylesheet" href="{{ asset('css/dapickerbootstrap.css') }}">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mobile.css">

	<!--[if IE 9]>
	<script>
		window.location = "packages/oldbrowser/index.html";
	</script>

	<script src="libs/oldbrowser/html5shiv/es5-shim.min.js"></script>
	<script src="libs/oldbrowser/html5shiv/html5shiv.min.js"></script>
	<script src="libs/oldbrowser/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="libs/oldbrowser/respond/respond.min.js"></script>
	<![endif]-->
</head>