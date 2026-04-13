@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($summary['income_total'])</h3>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">@currency($summary['expense_total'])</h3>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Draft</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $summary['draft_count'] }}</h3>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Transaksi dengan Nota</p>
                    <h3 class="mt-3 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $summary['receipt_count'] }}</h3>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Filter Transaksi</h3>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                        Gunakan filter untuk mempersempit pencarian transaksi.
                    </p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-theme-sm font-medium text-brand-500">
                    Reset
                </a>
            </div>

            <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Referensi, partner, deskripsi"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:text-white/90" />
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                    <select name="type"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua</option>
                        @foreach (config('finance.transaction_types') as $key => $label)
                            <option value="{{ $key }}" @selected(($filters['type'] ?? '') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua</option>
                        @foreach (config('finance.transaction_statuses') as $key => $label)
                            <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Akun</label>
                    <select name="account_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua akun</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected(($filters['account_id'] ?? '') == $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <select name="category_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-3">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Unit Bisnis</label>
                    <select name="business_unit_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Semua unit</option>
                        @foreach ($businessUnits as $businessUnit)
                            <option value="{{ $businessUnit->id }}" @selected(($filters['business_unit_id'] ?? '') == $businessUnit->id)>
                                {{ $businessUnit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-2">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Sampai</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                </div>
                <div class="col-span-12 flex items-end justify-end">
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Transaksi</h3>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                        Input, cek, edit, dan rapikan transaksi harian Anda di sini.
                    </p>
                </div>
                <a href="{{ route('transactions.create') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Tambah transaksi
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Transaksi</th>
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Akun / Unit</th>
                            <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nominal</th>
                            <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                <td class="px-4 py-4 text-theme-sm text-gray-600 dark:text-gray-300">
                                    {{ $transaction->transaction_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="mt-0.5 rounded-full px-3 py-1 text-theme-xs font-medium {{ $transaction->type === 'income' ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-error-50 text-error-700 dark:bg-error-500/10 dark:text-error-300' }}">
                                            {{ config('finance.transaction_types.' . $transaction->type) }}
                                        </span>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-white/90">
                                                {{ $transaction->description ?: ($transaction->counterparty ?: 'Tanpa keterangan') }}
                                            </p>
                                            <p class="mt-1 text-theme-xs text-gray-500 dark:text-gray-400">
                                                {{ $transaction->category?->name ?? '-' }}
                                                @if ($transaction->reference_number)
                                                    · Ref: {{ $transaction->reference_number }}
                                                @endif
                                            </p>
                                            @if ($transaction->receipt_path)
                                                <a href="{{ asset('storage/' . $transaction->receipt_path) }}" target="_blank"
                                                    class="mt-2 inline-flex text-theme-xs font-medium text-brand-500">
                                                    Lihat nota
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $transaction->account?->name ?? '-' }}
                                    </p>
                                    <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                        {{ $transaction->businessUnit?->name ?? 'Tanpa unit bisnis' }}
                                    </p>
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
                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('transactions.edit', $transaction) }}"
                                            class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-200 px-3 text-theme-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                            Edit
                                        </a>
                                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST"
                                            onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-9 items-center justify-center rounded-lg border border-error-200 px-3 text-theme-xs font-medium text-error-600 hover:bg-error-50 dark:border-error-500/20 dark:text-error-300 dark:hover:bg-error-500/10">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-4 py-10 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                    Belum ada transaksi. Mulai dengan menambahkan transaksi pertama.
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
