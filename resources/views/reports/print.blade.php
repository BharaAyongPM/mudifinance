<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset(config('finance.logo_path')) }}">
    <title>{{ $title }} | {{ config('finance.company_name') }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 print:bg-white">
    @php
        $logoPath = config('finance.logo_path');
        $hasLogo = file_exists(public_path($logoPath));
    @endphp

    <div class="mx-auto max-w-6xl p-4 print:p-0">
        <div class="mb-4 flex justify-end gap-3 print:hidden">
            <a href="{{ route('reports.index', request()->query()) }}"
                class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 bg-white px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50">
                Kembali
            </a>
            <button type="button" onclick="window.print()"
                class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                Cetak sekarang
            </button>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-theme-sm print:rounded-none print:border-0 print:p-0">
            <div class="flex flex-col gap-5 border-b border-gray-200 pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-center gap-4">
                    @if ($hasLogo)
                        <img src="{{ asset($logoPath) }}" alt="{{ config('finance.company_name') }}"
                            class="h-16 w-16 rounded-2xl border border-gray-200 object-contain p-2" />
                    @endif
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">{{ config('finance.company_name') }}</h1>
                        <p class="mt-1 text-theme-sm text-gray-500">{{ config('finance.app_name') }} - Laporan Keuangan</p>
                        <p class="text-theme-sm text-gray-500">Pemilik data: {{ auth()->user()->name }}</p>
                    </div>
                </div>

                <div class="text-theme-sm text-gray-500">
                    <p>Periode: {{ \Illuminate\Support\Carbon::parse($filters['date_from'])->translatedFormat('d M Y') }} -
                        {{ \Illuminate\Support\Carbon::parse($filters['date_to'])->translatedFormat('d M Y') }}</p>
                    <p>Dicetak: {{ now()->translatedFormat('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ($filterLabels as $label)
                    @if ($label)
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-theme-xs font-medium text-gray-700">
                            {{ $label }}
                        </span>
                    @endif
                @endforeach
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="rounded-2xl border border-gray-200 p-4">
                    <p class="text-theme-sm text-gray-500">Pemasukan</p>
                    <p class="mt-2 text-lg font-semibold text-gray-800">@currency($summary['income_total'])</p>
                </div>
                <div class="rounded-2xl border border-gray-200 p-4">
                    <p class="text-theme-sm text-gray-500">Pengeluaran</p>
                    <p class="mt-2 text-lg font-semibold text-gray-800">@currency($summary['expense_total'])</p>
                </div>
                <div class="rounded-2xl border border-gray-200 p-4">
                    <p class="text-theme-sm text-gray-500">Net Cashflow</p>
                    <p class="mt-2 text-lg font-semibold text-gray-800">@currency($summary['net_total'])</p>
                </div>
                <div class="rounded-2xl border border-gray-200 p-4">
                    <p class="text-theme-sm text-gray-500">Jumlah Transaksi</p>
                    <p class="mt-2 text-lg font-semibold text-gray-800">{{ $summary['transaction_count'] }}</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 p-5">
                    <h3 class="text-lg font-semibold text-gray-800">Breakdown Kategori</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($categoryBreakdown->take(8) as $category)
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="h-3 w-3 rounded-full" style="background-color: {{ $category->color ?: '#465FFF' }}"></span>
                                    <span class="text-theme-sm text-gray-700">{{ $category->name }}</span>
                                </div>
                                <span class="text-theme-sm font-medium text-gray-800">
                                    {{ Number::currency((float) $category->total_amount, 'IDR', 'id') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-theme-sm text-gray-500">Tidak ada data kategori.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 p-5">
                    <h3 class="text-lg font-semibold text-gray-800">Breakdown Unit Bisnis</h3>
                    <div class="mt-4 space-y-3">
                        @forelse ($businessUnitBreakdown->take(8) as $businessUnit)
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-theme-sm font-medium text-gray-800">{{ $businessUnit->name }}</p>
                                    <p class="text-theme-xs text-gray-500">{{ $businessUnit->code ?: 'Tanpa kode' }}</p>
                                </div>
                                <span class="text-theme-sm font-medium text-gray-800">
                                    {{ Number::currency((float) $businessUnit->total_amount, 'IDR', 'id') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-theme-sm text-gray-500">Tidak ada data unit bisnis.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800">Detail Transaksi</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-3 py-3 text-left text-theme-xs font-medium text-gray-500">Tanggal</th>
                                <th class="px-3 py-3 text-left text-theme-xs font-medium text-gray-500">Deskripsi</th>
                                <th class="px-3 py-3 text-left text-theme-xs font-medium text-gray-500">Akun</th>
                                <th class="px-3 py-3 text-left text-theme-xs font-medium text-gray-500">Unit</th>
                                <th class="px-3 py-3 text-right text-theme-xs font-medium text-gray-500">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr class="border-b border-gray-100">
                                    <td class="px-3 py-3 text-theme-sm text-gray-700">
                                        {{ $transaction->transaction_date->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <p class="text-theme-sm font-medium text-gray-800">
                                            {{ $transaction->description ?: ($transaction->counterparty ?: 'Tanpa keterangan') }}
                                        </p>
                                        <p class="text-theme-xs text-gray-500">
                                            {{ $transaction->category?->name ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-700">{{ $transaction->account?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-theme-sm text-gray-700">{{ $transaction->businessUnit?->name ?? '-' }}</td>
                                    <td
                                        class="px-3 py-3 text-right text-theme-sm font-medium {{ $transaction->type === 'income' ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}
                                        {{ Number::currency((float) $transaction->amount, 'IDR', 'id') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-theme-sm text-gray-500">
                                        Tidak ada transaksi untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
