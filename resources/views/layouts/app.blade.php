<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="flex flex-col sm:flex-row">
            <x-navigation-layout />
            <main class="flex max-h-screen w-full flex-col overflow-x-hidden p-6 sm:p-12">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
