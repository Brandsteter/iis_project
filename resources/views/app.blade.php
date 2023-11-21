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
                border: none;
                padding: 10px;

            }
            .nav-button {
              width: 100%;
            }
        </style>

    </head>
    <body>
        <div class="d-flex flex-wrap" style="height: 100vh" id="app">
            <nav class="col-2 bg-light-blue-accent-1">
                <ul class="navigation" style="list-style-type: none; padding: 0; ">
                    <li class="nav-button">
                        <v-btn class="w-100" href="/event">Events</v-btn>
                    </li>

                    @auth
                    <li>
                      <v-btn class="w-100" href="/calendar">My events</v-btn>
                    </li>
                    @endauth

                    @auth
                    <li>
                      <v-btn class="w-100" href="/calendar">My calendar</v-btn>
                    </li>
                    @endauth

                    <li>
                        <v-btn class="w-100" href="/category">Event Categories</v-btn>
                    </li>

                    @auth
                    <li>
                        <v-btn class="w-100" href="/place">Event places</v-btn>
                    </li>
                    @endauth

                    @auth
                    @if(auth()->user()->isRole(\App\Enums\RoleEnum::Admin))
                    <li>
                        <v-btn class="w-100" href="/admin">Admin Panel</v-btn>
                    </li>
                    @endif
                    @endauth

                    @auth
                    <li>
                        <v-btn class="w-100" href="/auth/logout">Logout</v-btn>
                    </li>
                    @endauth

                    @guest
                    <li>
                        <v-btn class="w-100" href="/auth/login">Login</v-btn>
                    </li>
                    <li>
                        <v-btn class="w-100" href="/auth/register">Register</v-btn>
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
