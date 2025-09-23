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
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        {{-- <x-customer-navigation-layout /> --}}
        <livewire:layout.customer-navigation />

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('productModal', {
                selectedProduct: null,
                open(product) {
                    this.selectedProduct = product;
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: 'product-view'
                    }));
                },
                close() {
                    this.selectedProduct = null;
                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: 'product-view'
                    }));
                }
            });
        });
    </script>
    @livewire('notifications')
    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>
