<!DOCTYPE html>
<html lang="ru">
@include('partials._head')
<body>
@include('partials._header')
@include('partials._alerts')
@yield('content')
@include('partials._modal')
@include('partials._footer')
</body>
</html>