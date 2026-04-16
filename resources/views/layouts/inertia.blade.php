<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @inertiaHead

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .item {
            cursor: pointer;
        }

        .menu-top {
            z-index: 90;
            background-color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 50px;
            width: 100%;
            padding-left: 50px;
            border-bottom: thin solid rgba(120, 120, 120, 0.3);
        }

        .menu-top h2 {
            padding: 4px 10px;
            display: inline-flex;
        }

        .menu-top .item {
            float: right;
            min-width: 50px;
            height: 50px;
            text-align: center;
            padding: 10px;
            font-size: 20px;
            border-left: thin solid rgba(120, 120, 120, 0.3);
        }

        .menu-top .item:hover,
        .submenu-left .item:hover {
            color: #ee7b00;
            border-bottom: thin solid #ee7b00;

        }

        .menu-left {
            z-index: 100;
            background-color: #111d3b;
            position: fixed;
            height: 100%;
            width: 50px;
        }

        .menu-left .item {
            width: 50px;
            height: 50px;
            text-align: center;
            color: white;
            padding: 14px 0;
            font-size: 20px;
        }

        .menu-left .item:hover {
            background-color: #ee7b00;
            color: white;
        }

        .submenu-left {
            z-index: 80;
            background-color: rgba(120, 120, 120, 0.1);
            border-right: thin solid rgba(120, 120, 120, 0.3);
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 350px;
            padding-left: 50px;
            padding-top: 50px;
        }

        .submenu-left .item {
            padding: 10px;
            font-size: 16px;
            border-bottom: thin solid rgba(120, 120, 120, 0.3);
        }

        .content {
            position: fixed;
            top: 0;
            left: 0;
            padding-left: 350px;
            padding-top: 50px;
            width: 100%;
            height: 100%;
            overflow: auto;
        }
    </style>
</head>

<body>
    <div class="menu-top">
        <h2>
            <span style="color: #F09206">App</span>Name
        </h2>
        <div class="item">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="item">
            <i class="bi bi-search"></i>
        </div>
        <div class="item">
            <i class="bi bi-globe-europe-africa"></i>
        </div>
        <div class="item">
            <i class="bi bi-buildings-fill"></i>
        </div>
    </div>
    <div class="menu-left">
        <div class="item">
            <i class="bi bi-house-fill"></i>
        </div>
        <div class="item">
            <i class="bi bi-cloud-check-fill"></i>
        </div>
        <div class="item">
            <i class="bi bi-person-fill"></i>
        </div>

    </div>
    <div class="submenu-left">
        <div class="item">Hier komt item 1</div>
        <div class="item">Nog iets anders</div>
        <div class="item">Veel menu</div>
        <div class="item">Bla bla</div>
        <div class="item">Ja hier ook</div>

    </div>
    <div class="content">
        @inertia
    </div>
</body>

</html>