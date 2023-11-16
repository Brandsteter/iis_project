<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Icons -->
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
        <style>
            .page-container {
                display: flex; /* Use flexbox to position elements */
                justify-content: center; /* Center items horizontally */
                align-items: center; /* Center items vertically */
                height: 100vh; /* Set height to 100% of viewport height */
            }
        </style>

    </head>
    <body>
        @auth
            <a href="/auth/logout"><button> logout</button></a>
        @endauth
        <div id="app">
            @yield("content")
                <div class="page-container">
                    <root></root>
                </div>
        </div>
    </body>
</html>
