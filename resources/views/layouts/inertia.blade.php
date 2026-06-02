<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @inertiaHead

    <title>{{ config('app.name', 'Laravel') }}</title>
    @routes
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

    <div class="wrapper">
        <div class="mobile-overlay" onclick="toggleSubmenu()"></div>

        @include('layouts.nav_sidebar')
        @include('layouts.nav_submenu')
        @include('layouts.nav_topbar')

        <div class="dashboard-shell">
            <main class="content">
                <div class="content-frame">
                    <div class="content-main">
                        @inertia
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('layouts.nav_scripts')

</body>

</html>
