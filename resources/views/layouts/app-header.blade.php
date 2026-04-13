<header
    class="sticky top-0 z-99999 flex w-full border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="flex w-full flex-col gap-3 px-3 py-3 xl:flex-row xl:items-center xl:justify-between xl:px-6 lg:py-4">
        <div class="flex items-center gap-3">
            <button
                class="flex xl:hidden items-center justify-center h-10 w-10 rounded-lg text-gray-500 dark:text-gray-400"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': $store.sidebar.isMobileOpen }"
                @click="$store.sidebar.toggleMobileOpen()" aria-label="Toggle Mobile Menu">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
                        fill="currentColor"></path>
                </svg>
            </button>

            <button
                class="hidden xl:flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 dark:border-gray-800 dark:text-gray-400"
                :class="{ 'bg-gray-100 dark:bg-white/[0.03]': !$store.sidebar.isExpanded }"
                @click="$store.sidebar.toggleExpanded()" aria-label="Toggle Sidebar">
                <svg width="16" height="12" viewBox="0 0 16 12" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.583252 1C0.583252 0.585788 0.919038 0.25 1.33325 0.25H14.6666C15.0808 0.25 15.4166 0.585786 15.4166 1C15.4166 1.41421 15.0808 1.75 14.6666 1.75L1.33325 1.75C0.919038 1.75 0.583252 1.41422 0.583252 1ZM0.583252 11C0.583252 10.5858 0.919038 10.25 1.33325 10.25H14.6666C15.0808 10.25 15.4166 10.5858 15.4166 11C15.4166 11.4142 15.0808 11.75 14.6666 11.75L1.33325 11.75C0.919038 11.75 0.583252 11.4142 0.583252 11ZM1.33325 5.25C0.919038 5.25 0.583252 5.58579 0.583252 6C0.583252 6.41421 0.919038 6.75 1.33325 6.75L7.99992 6.75C8.41413 6.75 8.74992 6.41421 8.74992 6C8.74992 5.58579 8.41413 5.25 7.99992 5.25L1.33325 5.25Z"
                        fill="currentColor"></path>
                </svg>
            </button>

            <div>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ config('finance.app_name') }}</p>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center xl:justify-end">
            <form action="{{ route('transactions.index') }}" method="GET" class="w-full xl:w-[380px]">
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                            fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z"
                                fill="" />
                        </svg>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari transaksi..."
                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Laporan
                </a>

                <a href="{{ route('transactions.create') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-4 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Tambah Transaksi
                </a>

                <button
                    class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
                    @click="$store.theme.toggle()">
                    <svg class="hidden dark:block" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915C9.58577 4.2915 9.24998 3.95572 9.24998 3.5415V2.2915C9.24998 1.87729 9.58577 1.5415 9.99998 1.5415ZM10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327Z"
                            fill="currentColor" />
                    </svg>
                    <svg class="dark:hidden" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path
                            d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97ZM8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459Z"
                            fill="currentColor" />
                        <path
                            d="M12.9154 13.0035C9.64678 13.0035 6.99707 10.3538 6.99707 7.08524H5.49707C5.49707 11.1823 8.81835 14.5035 12.9154 14.5035V13.0035Z"
                            fill="currentColor" />
                    </svg>
                </button>

                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button
                        class="flex h-11 items-center gap-3 rounded-full border border-gray-200 bg-white pl-2 pr-4 text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                        @click="open = ! open">
                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-500 text-xs font-semibold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-theme-sm font-medium">{{ auth()->user()->name }}</span>
                            <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                                {{ config('finance.roles.' . auth()->user()->role) }}
                            </span>
                        </span>
                    </button>

                    <div x-show="open" x-cloak
                        class="absolute right-0 mt-3 w-80 rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ auth()->user()->name }}</p>
                        <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                        <p class="mt-1 text-theme-xs text-brand-500">{{ config('finance.roles.' . auth()->user()->role) }}</p>

                        <div class="mt-4 flex flex-col gap-2">
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('users.index') }}"
                                    class="inline-flex h-10 items-center rounded-lg border border-gray-200 px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                    Kelola User
                                </a>
                            @endif

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex h-10 w-full items-center justify-center rounded-lg bg-error-500 px-4 text-theme-sm font-medium text-white hover:bg-error-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
