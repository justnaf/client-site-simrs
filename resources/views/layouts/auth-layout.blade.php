<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Gradasi latar belakang animasi (tetap sama) */
        @keyframes gradient-fade {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .animated-gradient {
            background: linear-gradient(-45deg, #76A9C5, #5a8ca7, #88cde0, #a0d2e7);
            background-size: 400% 400%;
            animation: gradient-fade 12s ease infinite;
        }

    </style>
</head>
<body>
    <div class="min-h-screen animated-gradient flex items-center justify-center">
        {{ $slot }}
    </div>
</body>
</html>
