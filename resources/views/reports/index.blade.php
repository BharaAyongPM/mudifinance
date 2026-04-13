@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Filter Laporan</h3>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                        Tentukan periode dan dimensi laporan untuk melihat performa keuangan yang Anda butuhkan.
                    </p>
                </div>
                <a href="{{ route('reports.print', request()->query()) }}" target="_blank"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Cetak laporan
                </a>
            </div>

            <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Dari</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Sampai</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                    <select name="type"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua</option>
                        @foreach (config('finance.transaction_types') as $key => $label)
                            <option value="{{ $key }}" @selected($filters['type'] === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Akun</label>
                    <select name="account_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua akun</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected($filters['account_id'] == $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <select name="category_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($filters['category_id'] == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Unit Bisnis</label>
                    <select name="business_unit_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua unit</option>
                        @foreach ($businessUnits as $businessUnit)
                            <option value="{{ $businessUnit->id }}" @selected($filters['business_unit_id'] == $businessUnit->id)>
                                {{ $businessUnit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 flex justify-end">
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                        Tampilkan laporan
                    </button>
                </div>
            </form>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($filterLabels as $label)
                    @if ($label)
                        <span
                            class="rounded-full bg-gray-100 px-3 py-1 text-theme-xs font-medium text-gray-700 dark:bg-white/[0.05] dark:text-gray-300">
                            {{ $label }}
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Pemasukan</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($summary['income_total'])</h3>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Pengeluaran</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($summary['expense_total'])</h3>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Net Cashflow</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($summary['net_total'])</h3>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $summary['transaction_count'] }}</h3>
                </div>
            </div>

            <div class="col-span-12 xl:col-span-8">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tren Laporan</h3>
                    <p class="mb-5 text-theme-sm text-gray-500 dark:text-gray-400">
                        Visualisasi pemasukan dan pengeluaran sesuai filter laporan.
                    </p>
                    <div id="reportTrendChart" data-chart="finance-area" data-labels='@json($trend['labels'])'
                        data-income='@json($trend['income'])' data-expense='@json($trend['expense'])' class="h-88"></div>
                </div>
            </div>

            <div class="col-span-12 xl:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Kategori Dominan</h3>
                    <p class="mb-5 text-theme-sm text-gray-500 dark:text-gray-400">
                        Nilai transaksi terbesar berdasarkan kategori pada filter aktif.
                    </p>

                    @if ($categoryBreakdown->isNotEmpty())
                        <div id="reportCategoryChart" data-chart="finance-donut"
                            data-labels='@json($categoryBreakdown->pluck('name')->values())'
                            data-series='@json($categoryBreakdown->pluck('total_amount')->map(fn ($amount) => (float) $amount)->values())'
                            data-colors='@json($categoryBreakdown->pluck('color')->map(fn ($color) => $color ?: '#465FFF')->values())'
                            class="h-76"></div>
                    @else
                        <div
                            class="flex h-76 items-center justify-center rounded-2xl border border-dashed border-gray-200 text-theme-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
                            Tidak ada data kategori untuk filter ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Breakdown Akun</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($accountBreakdown as $account)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white/90">{{ $account->name }}</p>
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                        {{ config('finance.account_types.' . $account->type) }}
                                    </p>
                                </div>
                                <p class="text-right font-medium text-gray-800 dark:text-white/90">
                                    {{ Number::currency((float) $account->total_amount, 'IDR', 'id') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Tidak ada data akun.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Breakdown Kategori</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($categoryBreakdown as $category)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full" style="background-color: {{ $category->color ?: '#465FFF' }}"></span>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $category->name }}</p>
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                            {{ config('finance.transaction_types.' . $category->type) }}
                                        </p>
                                    </div>
                                </div>
                                <p class="text-right font-medium text-gray-800 dark:text-white/90">
                                    {{ Number::currency((float) $category->total_amount, 'IDR', 'id') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Tidak ada data kategori.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Breakdown Unit Bisnis</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($businessUnitBreakdown as $businessUnit)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-800">
                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white/90">{{ $businessUnit->name }}</p>
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $businessUnit->code ?: 'Tanpa kode' }}</p>
                                </div>
                                <p class="text-right font-medium text-gray-800 dark:text-white/90">
                                    {{ Number::currency((float) $businessUnit->total_amount, 'IDR', 'id') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">Tidak ada data unit bisnis.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Detail Transaksi Laporan</h3>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Detail transaksi sesuai filter di atas.
            </p>

            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</th>
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Akun</th>
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Unit</th>
                            <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                <td class="px-4 py-4 text-theme-sm text-gray-600 dark:text-gray-300">
                                    {{ $transaction->transaction_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-medium text-gray-800 dark:text-white/90">
                                        {{ $transaction->description ?: ($transaction->counterparty ?: 'Tanpa keterangan') }}
                                    </p>
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                        {{ $transaction->category?->name ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-theme-sm text-gray-600 dark:text-gray-300">
                                    {{ $transaction->account?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-theme-sm text-gray-600 dark:text-gray-300">
                                    {{ $transaction->businessUnit?->name ?? '-' }}
                                </td>
                                <td
                                    class="px-4 py-4 text-right font-medium {{ $transaction->type === 'income' ? 'text-success-600 dark:text-success-400' : 'text-error-600 dark:text-error-400' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}
                                    {{ Number::currency((float) $transaction->amount, 'IDR', 'id') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-10 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                    Tidak ada transaksi untuk filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="mt-5">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
