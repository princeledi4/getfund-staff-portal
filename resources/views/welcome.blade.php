<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assets/media/logo/getfund-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite('resources/css/app.css')
        @livewireStyles
    </head>
    <body class="antialiased" data-theme="light">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center selection:bg-red-500 selection:text-white" style="background-color: var(--bg-primary);">

            <!-- Theme Toggle Button - Fixed Position -->
            <div class="fixed top-6 right-6 z-50">
                <div class="staff-theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                    <span class="theme-icon">☀️</span>
                </div>
            </div>

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center mb-8">
                    <div class="staff-logo" style="width: 80px; height: 80px;">
                        <img src="{{ asset('assets/media/logo/getfund-logo.png') }}" alt="Getfund logo">
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2" style="color: var(--text-primary);">Staff Verification Portal</h1>
                    <p class="text-sm font-semibold tracking-wide" style="color: var(--text-secondary);">GHANA EDUCATION TRUST FUND</p>
                </div>

                <div class="mt-5">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">
                        <div class="staff-card motion-safe:hover:scale-[1.01] transition-all duration-250">
                            <livewire:staff.search />
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm sm:text-left" style="color: var(--text-secondary);">
                        <div class="flex items-center gap-4">
                            <a href="javascript:void(0)" class="group inline-flex items-center hover:opacity-80 transition-opacity">
                                Copyright &copy; {{ now()->format('Y') }} Getfund
                            </a>
                        </div>
                    </div>

                    <div class="ml-4 text-center text-sm sm:text-right sm:ml-0" style="color: var(--text-secondary);">
                        Powered by: <a href="javascript:void(0)" class="hover:opacity-80 transition-opacity" target="_blank">GETFund</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleTheme() {
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                html.setAttribute('data-theme', newTheme);
                document.body.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            }

            // Load saved theme on page load
            document.addEventListener('DOMContentLoaded', function() {
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
                document.body.setAttribute('data-theme', savedTheme);
            });
        </script>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
