@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $editing->exists ? 'Edit Akun Kas' : 'Tambah Akun Kas' }}
                </h3>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                    Buat akun kas, bank, atau e-wallet untuk memisahkan arus dana.
                </p>

                <form action="{{ $editing->exists ? route('accounts.update', $editing) : route('accounts.store') }}" method="POST"
                    class="mt-5 space-y-4">
                    @csrf
                    @if ($editing->exists)
                        @method('PUT')
                    @endif

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Nama Akun</label>
                        <input type="text" name="name" value="{{ old('name', $editing->name) }}"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        @error('name')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                        <select name="type"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                            @foreach (config('finance.account_types') as $key => $label)
                                <option value="{{ $key }}" @selected(old('type', $editing->type) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Nomor Akun</label>
                        <input type="text" name="account_number" value="{{ old('account_number', $editing->account_number) }}"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Saldo Awal</label>
                        <input type="number" step="0.01" min="0" name="opening_balance"
                            value="{{ old('opening_balance', $editing->opening_balance ?? 0) }}"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        @error('opening_balance')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">{{ old('description', $editing->description) }}</textarea>
                    </div>

                    <label class="flex items-center gap-3 text-theme-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing->is_active ?? true))
                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        Akun aktif
                    </label>

                    <div class="flex gap-3">
                        @if ($editing->exists)
                            <a href="{{ route('accounts.index') }}"
                                class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                Batal
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                            {{ $editing->exists ? 'Simpan perubahan' : 'Tambah akun' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Akun</h3>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                    Pantau saldo dan penggunaan transaksi pada tiap akun.
                </p>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Akun</th>
                                <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Saldo Awal</th>
                                <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Saldo Saat Ini</th>
                                <th class="px-4 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Transaksi</th>
                                <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($accounts as $account)
                                <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $account->name }}</p>
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">
                                            {{ config('finance.account_types.' . $account->type) }}
                                            @if ($account->account_number)
                                                · {{ $account->account_number }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-4 py-4 text-right text-gray-700 dark:text-gray-300">
                                        {{ Number::currency((float) $account->opening_balance, 'IDR', 'id') }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-medium text-gray-800 dark:text-white/90">
                                        {{ Number::currency((float) $account->current_balance, 'IDR', 'id') }}
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-700 dark:text-gray-300">
                                        {{ $account->transactions_count }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('accounts.index', ['edit' => $account->id]) }}"
                                                class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-200 px-3 text-theme-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                                Edit
                                            </a>
                                            <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                                                onsubmit="return confirm('Hapus akun ini?')">
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
                                    <td colspan="5"
                                        class="px-4 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                        Belum ada akun kas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
