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
            .navigation li{
                border: 1px solid black;
                padding: 10px;

            }
        </style>

    </head>
    <body>
        <div class="d-flex flex-wrap" style="height: 100vh" id="app">
            <nav class="col-3 bg-light-blue-accent-1">
                <ul class="navigation" style="list-style-type: none; padding:0">
                    <li>
                        <a href="/event">Events</a>
                    </li>

                    @auth
                    <li>
                        <a href="/calendar">My calendar</a>
                    </li>
                    @endauth

                    <li>
                        <a href="/">Event Categories</a>
                    </li>

                    @auth
                    <li>
                        <a href="/place">Event places</a>
                    </li>
                    @endauth

                    @auth
                        @if(auth()->user()->isRole(\App\Enums\RoleEnum::Admin))
                    <li>
                        <a href="/admin">Admin</a>
                    </li>
                    @endif
                    @endauth

                    @auth
                        <li>
                            <a href="/auth/logout">logout</a>
                        </li>
                    @endauth

                    @guest
                        <li>
                            <a href="/auth/login">login</a>
                        </li>
                        <li>
                            <a href="/auth/register">register</a>
                        </li>
                    @endguest
                </ul>
            </nav>
            <div class="col-9 p-2">
                @yield("content")
            </div>
        </div>
    </body>
</html>
