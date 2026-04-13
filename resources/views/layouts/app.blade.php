<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset(config('finance.logo_path')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('finance.logo_path')) }}">

    <title>{{ $title ?? config('finance.app_name') }} | {{ config('finance.company_name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;

                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,
                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },
                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },
                setMobileOpen(value) {
                    this.isMobileOpen = value;
                },
                setHovered(value) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = value;
                    }
                }
            });
        });
    </script>

    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            }
        })();
    </script>
</head>

<body x-data="{ loaded: true }"
    x-init="$store.theme.init(); $store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkViewport = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkViewport);">

    <x-common.preloader />

    <div class="min-h-screen xl:flex">
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            @include('layouts.app-header')

            <main class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
                @if (session('success'))
                    <div
                        class="mb-6 rounded-2xl border border-success-200 bg-success-25 px-4 py-4 text-success-700 dark:border-success-500/20 dark:bg-success-500/10 dark:text-success-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 rounded-2xl border border-error-200 bg-error-25 px-4 py-4 text-error-700 dark:border-error-500/20 dark:bg-error-500/10 dark:text-error-300">
                        {{ session('error') }}
                    </div>
                @endif

                @if (isset($pageTitle))
                    <x-common.page-breadcrumb :page-title="$pageTitle" />
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
