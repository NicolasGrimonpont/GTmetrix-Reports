<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    {{-- Title Section --}}
    <title>@yield('title', $page_title ?? 'Gtmetrix Reports')</title>
    {{-- Meta Data --}}
    <meta name="description" content="@yield('page_description', $page_description ?? '')" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="vtnI-VKn4-UtCnqWYzmL_IP6kya-8yoXTC7aS1tUxk8" />
    {{-- Styles --}}
    <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">
</head>

<body>

    @include('frontend.layout.partial.header')

    @yield('content')

    @include('frontend.layout.partial.footer')

    {{-- Global JS --}}
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/scripts.js') }}" type="text/javascript"></script>

    {{-- Includable JS --}}
    @yield('scripts')

</body>

</html>
