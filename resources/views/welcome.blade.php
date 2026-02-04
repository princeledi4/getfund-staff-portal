<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Figtree', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('assets/media/logo/logo.png') }}" class="h-16 w-auto" alt="GETFund logo">
                </div>

                <!-- Search Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                    <livewire:staff.search />
                </div>

                <!-- Footer -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <p>Copyright &copy; {{ now()->format('Y') }} GETFund</p>
                    <p class="mt-2 sm:mt-0">
                        Powered by <a href="javascript:void(0)" class="text-amber-600 hover:text-amber-500 dark:text-amber-400 dark:hover:text-amber-300 font-medium">rCodez</a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
