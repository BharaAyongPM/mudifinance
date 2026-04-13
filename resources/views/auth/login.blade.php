<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset(config('finance.logo_path')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('finance.logo_path')) }}">

    <title>{{ $title }} | {{ config('finance.app_name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-gray-100 dark:bg-gray-950">
    @php
        $logoPath = config('finance.logo_path');
        $hasLogo = file_exists(public_path($logoPath));
        $copyrightName = config('finance.copyright_name', 'Mustika Digital Nusantara');
    @endphp

    <div class="grid min-h-screen lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
        <div class="relative hidden overflow-hidden bg-linear-to-br from-gray-950 via-gray-900 to-brand-900 px-10 py-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute inset-0">
                <div class="absolute -left-20 top-16 h-64 w-64 rounded-full bg-brand-500/20 blur-3xl"></div>
                <div class="absolute bottom-10 right-0 h-72 w-72 rounded-full bg-blue-light-500/15 blur-3xl"></div>
                <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>
            </div>

            <div class="relative flex items-center gap-4">
                @if ($hasLogo)
                    <img src="{{ asset($logoPath) }}" alt="{{ config('finance.app_name') }}"
                        class="h-16 w-16 rounded-3xl bg-white object-contain p-3 shadow-theme-lg" />
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-white text-xl font-semibold text-brand-600 shadow-theme-lg">
                        MF
                    </div>
                @endif

                <div>
                    <p class="text-lg font-semibold">{{ config('finance.app_name') }}</p>
                    <p class="text-theme-sm text-white/70">{{ config('finance.tagline') }}</p>
                </div>
            </div>

            <div class="relative max-w-2xl">
                <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-theme-xs font-medium text-white/90">
                    Portal Login Multi-User
                </span>
                <h1 class="mt-6 text-5xl font-semibold leading-tight">
                    Kelola arus kas, transaksi, dan laporan bisnis dari satu dashboard yang rapi.
                </h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-white/75">
                    Admin dapat mengatur user dan akses data, sementara setiap user bekerja di data keuangannya sendiri
                    tanpa tercampur. Login dibuat simpel, tetapi tampilannya tetap profesional dan siap dipakai harian.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-4 backdrop-blur-sm">
                        <span class="mt-1 flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M5 12.5L9.2 16.5L19 6.5" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-white">Akses rapi per role</p>
                            <p class="mt-1 text-theme-sm text-white/70">
                                Admin dan user punya alur kerja yang terpisah, jadi data tetap aman dan fokus.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-4 backdrop-blur-sm">
                        <span class="mt-1 flex h-10 w-10 items-center justify-center rounded-2xl bg-white/10 text-white">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M5 19V11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                <path d="M12 19V5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                <path d="M19 19V9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                <path d="M4 19H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-white">Siap untuk laporan</p>
                            <p class="mt-1 text-theme-sm text-white/70">
                                Pemasukan, pengeluaran, dan cetak laporan bisa langsung dipantau dari aplikasi yang sama.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative grid grid-cols-3 gap-4">
                <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-theme-xs uppercase tracking-[0.2em] text-white/60">Pencatatan</p>
                    <p class="mt-3 text-xl font-semibold">Cash In & Out</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-theme-xs uppercase tracking-[0.2em] text-white/60">Analitik</p>
                    <p class="mt-3 text-xl font-semibold">Laporan Cepat</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm">
                    <p class="text-theme-xs uppercase tracking-[0.2em] text-white/60">Branding</p>
                    <p class="mt-3 text-xl font-semibold">{{ $copyrightName }}</p>
                </div>
            </div>
        </div>

        <div class="relative flex items-center justify-center overflow-hidden px-6 py-8 sm:px-10">
            <div class="absolute inset-0">
                <div class="absolute left-0 top-8 h-52 w-52 rounded-full bg-brand-100 blur-3xl dark:bg-brand-500/10"></div>
                <div class="absolute bottom-0 right-0 h-64 w-64 rounded-full bg-blue-light-100 blur-3xl dark:bg-blue-light-500/10"></div>
            </div>

            <div class="relative w-full max-w-lg">
                <div class="mb-6 flex items-center gap-4 lg:hidden">
                    @if ($hasLogo)
                        <img src="{{ asset($logoPath) }}" alt="{{ config('finance.app_name') }}"
                            class="h-14 w-14 rounded-3xl border border-gray-200 bg-white object-contain p-2 shadow-theme-sm dark:border-gray-800 dark:bg-gray-900" />
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-brand-500 text-lg font-semibold text-white shadow-theme-sm">
                            MF
                        </div>
                    @endif

                    <div>
                        <p class="text-sm font-semibold text-brand-500">{{ config('finance.app_name') }}</p>
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">{{ $copyrightName }}</p>
                    </div>
                </div>

                <div class="rounded-[32px] border border-gray-200 bg-white/95 p-6 shadow-theme-xl backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/95 sm:p-8">
                    <div class="mb-8 flex items-start gap-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-3xl border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950">
                            @if ($hasLogo)
                                <img src="{{ asset($logoPath) }}" alt="{{ config('finance.app_name') }}"
                                    class="h-11 w-11 object-contain" />
                            @else
                                <span class="text-lg font-semibold text-brand-500">MF</span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-500">Secure Access</p>
                            <h2 class="mt-2 text-3xl font-semibold text-gray-800 dark:text-white/90">Masuk ke aplikasi</h2>
                            <p class="mt-2 text-theme-sm text-gray-500 dark:text-gray-400">
                                Gunakan email dan password yang sudah dibuat oleh admin untuk masuk ke dashboard Anda.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M4 7L10.94 11.958C11.5566 12.3984 12.4434 12.3984 13.06 11.958L20 7"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <rect x="3.5" y="5.5" width="17" height="13" rx="2.5"
                                            stroke="currentColor" stroke-width="1.7" />
                                    </svg>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="h-12 w-full rounded-2xl border border-gray-200 bg-transparent py-3 pl-12 pr-4 text-theme-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                            </div>
                            @error('email')
                                <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <rect x="4.5" y="10" width="15" height="9.5" rx="2.5"
                                            stroke="currentColor" stroke-width="1.7" />
                                        <path d="M8 10V7.5C8 5.29086 9.79086 3.5 12 3.5C14.2091 3.5 16 5.29086 16 7.5V10"
                                            stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                        <circle cx="12" cy="14.75" r="1.35" fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="password" name="password"
                                    class="h-12 w-full rounded-2xl border border-gray-200 bg-transparent py-3 pl-12 pr-4 text-theme-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                            </div>
                            @error('password')
                                <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex items-center gap-3 text-theme-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="remember" value="1"
                                class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                            Ingat saya di perangkat ini
                        </label>

                        <button type="submit"
                            class="inline-flex h-12 w-full items-center justify-center rounded-2xl bg-brand-500 px-5 text-theme-sm font-medium text-white shadow-theme-sm transition hover:bg-brand-600">
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <div class="mt-6 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            Akses user dibuat langsung oleh admin. Jika akun belum tersedia atau sedang nonaktif,
                            silakan hubungi admin aplikasi.
                        </p>
                    </div>

                    <div class="mt-6 flex items-center gap-3 rounded-2xl border border-gray-100 bg-white px-4 py-4 dark:border-gray-800 dark:bg-gray-950/70">
                        @if ($hasLogo)
                            <img src="{{ asset($logoPath) }}" alt="{{ $copyrightName }}"
                                class="h-12 w-12 rounded-2xl border border-gray-200 bg-white object-contain p-2 dark:border-gray-800 dark:bg-gray-900" />
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-500 text-sm font-semibold text-white">
                                MDN
                            </div>
                        @endif

                        <div>
                            <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $copyrightName }}</p>
                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                Copyright &copy; {{ now()->year }} {{ $copyrightName }}. All rights reserved.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
