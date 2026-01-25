<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Inertia Head + SEO -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/App.jsx'])
    @inertiaHead
</head>
<body class="font-sans antialiased bg-gray-100">
    <div id="app" data-page="{{ json_encode($page) }}"></div>
</body>
</html>
