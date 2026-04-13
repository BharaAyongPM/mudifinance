@extends('layouts.app')

@section('content')
    @php
        $selectedType = old('type', $transaction->type ?? 'income');
        $selectedCategory = old('category_id', $transaction->category_id);
        $categoriesForJs = $categories
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'type' => $category->type,
            ])
            ->values();
    @endphp

    <div class="space-y-6" x-data="transactionForm(@js($categoriesForJs), '{{ $selectedType }}', '{{ $selectedCategory }}')">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ $isEditing ? 'Perbarui transaksi' : 'Catat transaksi baru' }}
                    </h3>
                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                        Simpan pemasukan, pengeluaran, metode bayar, partner, dan lampiran nota dalam satu form.
                    </p>
                </div>
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ $isEditing ? route('transactions.update', $transaction) : route('transactions.store') }}"
            method="POST" enctype="multipart/form-data"
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
            @csrf
            @if ($isEditing)
                @method('PUT')
            @endif

            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                    <input type="date" name="transaction_date"
                        value="{{ old('transaction_date', optional($transaction->transaction_date)->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    @error('transaction_date')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                    <select name="type" x-model="type"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        @foreach (config('finance.transaction_types') as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        @foreach (config('finance.transaction_statuses') as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $transaction->status) === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Nominal</label>
                    <input type="number" step="0.01" min="0" name="amount"
                        value="{{ old('amount', $transaction->amount) }}"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    @error('amount')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Akun Kas</label>
                    <select name="account_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Pilih akun</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}" @selected(old('account_id', $transaction->account_id) == $account->id)>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('account_id')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Unit Bisnis</label>
                    <select name="business_unit_id"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Pilih unit bisnis</option>
                        @foreach ($businessUnits as $businessUnit)
                            <option value="{{ $businessUnit->id }}" @selected(old('business_unit_id', $transaction->business_unit_id) == $businessUnit->id)>
                                {{ $businessUnit->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('business_unit_id')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <select name="category_id" x-model="categoryId"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                        <option value="">Pilih kategori</option>
                        <template x-for="category in filteredCategories" :key="category.id">
                            <option :value="category.id" x-text="category.name"></option>
                        </template>
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Metode Pembayaran</label>
                    <input type="text" name="payment_method" value="{{ old('payment_method', $transaction->payment_method) }}"
                        placeholder="Transfer bank, cash, e-wallet"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    @error('payment_method')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Partner / Vendor / Customer</label>
                    <input type="text" name="counterparty" value="{{ old('counterparty', $transaction->counterparty) }}"
                        placeholder="Nama pihak terkait"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    @error('counterparty')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-4">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Nomor Referensi</label>
                    <input type="text" name="reference_number"
                        value="{{ old('reference_number', $transaction->reference_number) }}"
                        placeholder="INV-2026-001"
                        class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    @error('reference_number')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan transaksi ini..."
                        class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">{{ old('description', $transaction->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12">
                    <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Upload Nota</label>
                    <input type="file" name="receipt"
                        class="block w-full rounded-lg border border-dashed border-gray-300 px-4 py-4 text-theme-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-theme-sm file:font-medium file:text-brand-600 hover:file:bg-brand-100 dark:border-gray-800 dark:text-gray-300 dark:file:bg-white/[0.05] dark:file:text-white/90" />
                    <p class="mt-2 text-theme-xs text-gray-500 dark:text-gray-400">
                        Format yang didukung: JPG, PNG, WEBP, PDF. Maksimal 4 MB.
                    </p>
                    @if ($isEditing && $transaction->receipt_path)
                        <a href="{{ asset('storage/' . $transaction->receipt_path) }}" target="_blank"
                            class="mt-3 inline-flex text-theme-sm font-medium text-brand-500">
                            Lihat nota saat ini ({{ $transaction->receipt_original_name }})
                        </a>
                    @endif
                    @error('receipt')
                        <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 px-5 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    {{ $isEditing ? 'Simpan perubahan' : 'Simpan transaksi' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function transactionForm(categories, initialType, initialCategory) {
            return {
                categories,
                type: initialType || 'income',
                categoryId: initialCategory || '',
                get filteredCategories() {
                    return this.categories.filter((category) => category.type === this.type);
                },
                init() {
                    const stillExists = this.filteredCategories.some((category) => String(category.id) === String(this.categoryId));

                    if (!stillExists) {
                        this.categoryId = '';
                    }
                }
            }
        }
    </script>
@endpush
