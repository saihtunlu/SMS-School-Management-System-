<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" sizes="16x16" href="{{ asset('/storage/image/logo.gif')}}" />
    <title>SMS</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .loader {
            position: fixed;
            z-index: 99999999999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgb(255, 255, 255, 1);
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .logo_ {
            width: 80px;
            transition: 1s all;

        }

        .loader_ {
            width: 120px;
        }

        .loader.hidden {
            animation: fadeOut 1s;
            animation-fill-mode: forwards;

        }

        .loader.hidden.display_none {
            display: none !important;

        }

        @keyframes fadeOut {

            100% {
                opacity: 0;
                display: none;
            }
        }
    </style>
    <script>
        window.addEventListener("load", function() {
            const loader = document.querySelector(".loader");

            loader.className += " hidden";
            setTimeout(function() {
                loader.className += " display_none";
            }, 1000);
        });
    </script>
</head>

<body>
    <div class="loader">
        <div style="display: flex;flex-direction:column; align-items:center;">
            <img class="loader_" src="{{ asset('/storage/image/logo.gif')}}" alt="Loading...">
        </div>


    </div>
    @yield('content')
</body>

</html>