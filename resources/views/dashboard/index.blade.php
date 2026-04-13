@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <section
            class="overflow-hidden rounded-2xl bg-linear-to-r from-brand-500 via-brand-600 to-gray-900 p-6 text-white shadow-theme-lg">
            <div class="grid gap-6 xl:grid-cols-[1.4fr,0.9fr]">
                <div>
                    <span class="inline-flex rounded-full bg-white/10 px-3 py-1 text-theme-xs font-medium text-white/90">
                        Dashboard Arus Kas {{ $year }}
                    </span>
                    <h1 class="mt-4 max-w-2xl text-2xl font-semibold md:text-3xl">
                        Pusat kendali keuangan milik {{ $currentUser->name }} untuk memantau pemasukan, pengeluaran, dan arus kas.
                    </h1>
                    <p class="mt-3 max-w-2xl text-theme-sm text-white/75">
                        Dashboard ini menampilkan data keuangan akun Anda saja. Tambah unit bisnis, akun kas, kategori,
                        dan transaksi sesuai kebutuhan usaha masing-masing.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-theme-sm text-white/70">Total saldo kas</p>
                        <form action="{{ route('dashboard') }}" method="GET">
                            <select name="year" onchange="this.form.submit()"
                                class="rounded-lg border border-white/10 bg-white/10 px-3 py-2 text-theme-xs text-white outline-hidden">
                                @foreach ($yearOptions as $yearOption)
                                    <option value="{{ $yearOption }}" @selected((int) $yearOption === (int) $year)>
                                        {{ $yearOption }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <p class="mt-4 text-title-sm font-semibold">@currency($cashBalance)</p>
                    <p class="mt-2 text-theme-sm text-white/70">
                        Bulan ini:
                        <span class="font-medium text-success-300">{{ Number::currency($netThisMonth, 'IDR', 'id') }}</span>
                    </p>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <a href="{{ route('transactions.create') }}"
                            class="rounded-xl bg-white px-4 py-3 text-center text-theme-sm font-medium text-brand-700 hover:bg-white/90">
                            Input Transaksi
                        </a>
                        <a href="{{ route('reports.index') }}"
                            class="rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-center text-theme-sm font-medium text-white hover:bg-white/15">
                            Buka Laporan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($incomeThisMonth)</h3>
                    <p class="mt-2 text-theme-xs text-success-600 dark:text-success-400">Arus masuk tercatat</p>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($expenseThisMonth)</h3>
                    <p class="mt-2 text-theme-xs text-error-600 dark:text-error-400">Semua beban dan biaya</p>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Nota Belum Diunggah</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $receiptMissingCount }}</h3>
                    <p class="mt-2 text-theme-xs text-gray-500 dark:text-gray-400">Transaksi posted tanpa lampiran nota</p>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Draft Menunggu Posting</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $draftCount }}</h3>
                    <p class="mt-2 text-theme-xs text-warning-600 dark:text-warning-400">Cocok untuk transaksi yang masih diverifikasi</p>
                </div>
            </div>

            <div class="col-span-12 xl:col-span-8">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tren Arus Kas</h3>
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            Perbandingan pemasukan dan pengeluaran sepanjang {{ $year }}.
                        </p>
                    </div>

                    <div id="dashboardCashflowChart" data-chart="finance-area" data-labels='@json($monthlyLabels)'
                        data-income='@json($monthlyIncome)' data-expense='@json($monthlyExpense)' class="h-88"></div>
                </div>
            </div>

            <div class="col-span-12 xl:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Komposisi Pengeluaran</h3>
                    <p class="mb-5 text-theme-sm text-gray-500 dark:text-gray-400">
                        Kategori biaya terbesar tahun {{ $year }}.
                    </p>

                    @if ($expenseBreakdown->isNotEmpty())
                        <div id="dashboardExpenseChart" data-chart="finance-donut"
                            data-labels='@json($expenseBreakdown->pluck('name')->values())'
                            data-series='@json($expenseBreakdown->pluck('total_amount')->map(fn ($amount) => (float) $amount)->values())'
                            data-colors='@json($expenseBreakdown->pluck('color')->map(fn ($color) => $color ?: '#465FFF')->values())'
                            class="h-76"></div>
                    @else
                        <div
                            class="flex h-76 items-center justify-center rounded-2xl border border-dashed border-gray-200 text-theme-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
                            Belum ada pengeluaran tercatat di tahun ini.
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-span-12 xl:col-span-5">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Saldo per Akun</h3>
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">Kas, bank, dan e-wallet aktif.</p>
                    </div>

                    <div class="space-y-4">
                        @forelse ($accounts as $account)
                            <div class="rounded-2xl border border-gray-100 px-4 py-4 dark:border-gray-800">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $account->name }}</p>
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                            {{ config('finance.account_types.' . $account->type) }}
                                        </p>
                                    </div>
                                    <span
                                        class="rounded-full px-3 py-1 text-theme-xs font-medium {{ $account->current_balance >= 0 ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-error-50 text-error-700 dark:bg-error-500/10 dark:text-error-300' }}">
                                        {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>

                                <div class="mt-4 flex items-end justify-between gap-3">
                                    <div>
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">Saldo saat ini</p>
                                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">
                                            @currency($account->current_balance)
                                        </p>
                                    </div>
                                    <a href="{{ route('accounts.index', ['edit' => $account->id]) }}"
                                        class="text-theme-xs font-medium text-brand-500">
                                        Kelola
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-gray-200 px-4 py-8 text-center text-theme-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                Belum ada akun kas.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-span-12 xl:col-span-7">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Performa Unit Bisnis</h3>
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            Ringkasan pemasukan dan pengeluaran per unit bisnis.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Unit</th>
                                    <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Pemasukan</th>
                                    <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Pengeluaran</th>
                                    <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Net</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($businessUnits as $businessUnit)
                                    <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                        <td class="px-4 py-4">
                                            <p class="font-medium text-gray-800 dark:text-white/90">{{ $businessUnit->name }}</p>
                                            <p class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $businessUnit->code ?: 'Tanpa kode' }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-right text-gray-700 dark:text-gray-300">
                                            {{ Number::currency((float) ($businessUnit->total_income ?? 0), 'IDR', 'id') }}
                                        </td>
                                        <td class="px-4 py-4 text-right text-gray-700 dark:text-gray-300">
                                            {{ Number::currency((float) ($businessUnit->total_expense ?? 0), 'IDR', 'id') }}
                                        </td>
                                        <td
                                            class="px-4 py-4 text-right font-medium {{ $businessUnit->net_total >= 0 ? 'text-success-600 dark:text-success-400' : 'text-error-600 dark:text-error-400' }}">
                                            {{ Number::currency($businessUnit->net_total, 'IDR', 'id') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-4 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                            Belum ada unit bisnis.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-span-12">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Transaksi Terbaru</h3>
                            <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                                Aktivitas keuangan paling akhir yang masuk ke sistem.
                            </p>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="text-theme-sm font-medium text-brand-500">
                            Lihat semua
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Akun</th>
                                    <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $transaction)
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
                                                @if ($transaction->businessUnit)
                                                    | {{ $transaction->businessUnit->name }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="px-4 py-4 text-theme-sm text-gray-600 dark:text-gray-300">
                                            {{ $transaction->account?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="rounded-full px-3 py-1 text-theme-xs font-medium {{ $transaction->status === 'posted' ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-300' }}">
                                                {{ config('finance.transaction_statuses.' . $transaction->status) }}
                                            </span>
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
                                            class="px-4 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                            Belum ada transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
