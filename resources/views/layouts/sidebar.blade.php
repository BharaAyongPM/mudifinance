@php
    use App\Helpers\MenuHelper;

    $menuGroups = MenuHelper::getMenuGroups();
    $logoPath = config('finance.logo_path');
    $hasLogo = file_exists(public_path($logoPath));
@endphp

<aside id="sidebar"
    class="fixed left-0 top-0 z-99999 flex h-screen flex-col border-r border-gray-200 bg-white px-5 text-gray-900 transition-all duration-300 ease-in-out dark:border-gray-800 dark:bg-gray-900"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">
    <div class="flex pt-8 pb-7"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'xl:justify-center' : 'justify-start'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            @if ($hasLogo)
                <img src="{{ asset($logoPath) }}" alt="{{ config('finance.app_name') }}"
                    class="h-11 w-11 rounded-2xl border border-gray-200 bg-white object-contain p-2 shadow-theme-xs dark:border-gray-800 dark:bg-gray-800" />
            @else
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-500 text-sm font-semibold text-white shadow-theme-xs">
                    MD
                </div>
            @endif

            <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ config('finance.app_name') }}</p>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                    {{ auth()->user()->name }} &middot; {{ config('finance.roles.' . auth()->user()->role) }}
                </p>
            </div>
        </a>
    </div>

    <div class="no-scrollbar flex flex-1 flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mb-6">
            <div class="flex flex-col gap-4">
                @foreach ($menuGroups as $menuGroup)
                    <div>
                        <h2 class="mb-4 flex text-xs leading-[20px] uppercase text-gray-400"
                            :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'lg:justify-center' : 'justify-start'">
                            <template
                                x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                                <span>{{ $menuGroup['title'] }}</span>
                            </template>
                            <template x-if="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen">
                                <span class="h-1.5 w-1.5 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                            </template>
                        </h2>

                        <ul class="flex flex-col gap-1">
                            @foreach ($menuGroup['items'] as $item)
                                @php
                                    $path = trim(parse_url($item['path'], PHP_URL_PATH), '/');
                                    $active = $path === ''
                                        ? request()->routeIs('dashboard')
                                        : request()->is($path) || request()->is($path . '/*');
                                @endphp
                                <li>
                                    <a href="{{ $item['path'] }}" class="menu-item group"
                                        :class="[
                                            '{{ $active ? 'menu-item-active' : 'menu-item-inactive' }}',
                                            (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                            'xl:justify-center' : 'justify-start'
                                        ]">
                                        <span
                                            class="{{ $active ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}">
                                            {!! MenuHelper::getIconSvg($item['icon']) !!}
                                        </span>
                                        <span
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            x-cloak class="menu-item-text">
                                            {{ $item['name'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </nav>

        <div x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak
            class="mt-auto mb-8 rounded-2xl bg-gray-50 p-4 dark:bg-white/[0.03]">
            <p class="mb-2 text-sm font-semibold text-gray-800 dark:text-white/90">Butuh input cepat?</p>
            <p class="mb-4 text-theme-sm text-gray-500 dark:text-gray-400">
                Catat pemasukan atau pengeluaran begitu transaksi terjadi agar laporan selalu akurat.
            </p>
            <a href="{{ route('transactions.create') }}"
                class="flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-theme-sm font-medium text-white hover:bg-brand-600">
                Tambah transaksi
            </a>
        </div>
    </div>
</aside>
