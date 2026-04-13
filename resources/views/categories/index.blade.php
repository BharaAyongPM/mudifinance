@extends('layouts.app')

@php
    use App\Helpers\MenuHelper;

    $selectedColor = old('color', $editing->color ?: '#12B76A');
    $selectedIcon = old('icon', $editing->icon ?: 'wallet');
    $iconOptions = MenuHelper::getCategoryIconOptions();

    if ($selectedIcon && !array_key_exists($selectedIcon, $iconOptions)) {
        $iconOptions = [
            $selectedIcon => [
                'label' => 'Icon tersimpan',
                'description' => 'Nilai lama kategori ini tetap bisa dipakai sampai Anda menggantinya.',
            ],
            ...$iconOptions,
        ];
    }
@endphp

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]"
                x-data="{ color: @js($selectedColor), icon: @js($selectedIcon) }">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            {{ $editing->exists ? 'Edit Kategori' : 'Tambah Kategori' }}
                        </h3>
                        <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                            Pilih warna dan icon supaya kategori lebih cepat dikenali saat input transaksi dan laporan.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-right dark:border-gray-800 dark:bg-gray-900">
                        <p class="text-theme-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Preview Warna
                        </p>
                        <div class="mt-2 flex items-center justify-end gap-3">
                            <span class="h-6 w-6 rounded-full border border-white shadow-theme-xs"
                                :style="'background-color: ' + color"></span>
                            <span class="text-theme-sm font-semibold text-gray-800 dark:text-white/90" x-text="color"></span>
                        </div>
                    </div>
                </div>

                <form action="{{ $editing->exists ? route('categories.update', $editing) : route('categories.store') }}"
                    method="POST" class="mt-5 space-y-5">
                    @csrf
                    @if ($editing->exists)
                        @method('PUT')
                    @endif

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $editing->name) }}"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        @error('name')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Jenis</label>
                        <select name="type"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                            @foreach (config('finance.transaction_types') as $key => $label)
                                <option value="{{ $key }}" @selected(old('type', $editing->type) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label class="block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Warna</label>
                            <span class="text-theme-xs text-gray-500 dark:text-gray-400">Color picker + kode hex</span>
                        </div>

                        <input type="hidden" name="color" :value="color">

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <div class="flex items-center gap-3">
                                <span class="h-11 w-11 rounded-xl border border-gray-200 shadow-theme-xs dark:border-gray-800"
                                    :style="'background-color: ' + color"></span>
                                <input type="color" x-model="color"
                                    class="h-11 w-16 cursor-pointer rounded-xl border border-gray-200 bg-transparent p-1 dark:border-gray-800" />
                            </div>

                            <input type="text" x-model="color" placeholder="#12B76A"
                                class="h-11 flex-1 rounded-lg border border-gray-200 bg-transparent px-4 font-medium uppercase tracking-wide text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        </div>

                        @error('color')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-3">
                            <label class="block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Icon</label>
                            <p class="mt-1 text-theme-xs text-gray-500 dark:text-gray-400">
                                Saya siapkan icon yang paling sering dipakai untuk kategori keuangan, jadi tinggal pilih.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($iconOptions as $key => $iconOption)
                                <label class="block cursor-pointer">
                                    <input type="radio" name="icon" value="{{ $key }}" x-model="icon"
                                        class="sr-only">
                                    <div class="flex h-full items-start gap-3 rounded-2xl border px-4 py-3 transition"
                                        :class="icon === '{{ $key }}'
                                            ? 'border-brand-500 bg-brand-50 shadow-theme-xs dark:border-brand-400 dark:bg-brand-500/10'
                                            : 'border-gray-200 bg-white hover:border-brand-200 hover:bg-gray-50 dark:border-gray-800 dark:bg-transparent dark:hover:border-gray-700 dark:hover:bg-white/[0.03]'">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900"
                                            :style="icon === '{{ $key }}' ? 'color: ' + color : ''">
                                            {!! MenuHelper::getCategoryIconSvg($key) !!}
                                        </span>
                                        <span>
                                            <span class="block text-theme-sm font-semibold text-gray-800 dark:text-white/90">
                                                {{ $iconOption['label'] }}
                                            </span>
                                            <span class="mt-1 block text-theme-xs text-gray-500 dark:text-gray-400">
                                                {{ $iconOption['description'] }}
                                            </span>
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('icon')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-3 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">{{ old('description', $editing->description) }}</textarea>
                    </div>

                    <label class="flex items-center gap-3 rounded-2xl border border-gray-200 px-4 py-3 text-theme-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing->is_active ?? true))
                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        Kategori aktif
                    </label>

                    <div class="flex flex-wrap gap-3">
                        @if ($editing->exists)
                            <a href="{{ route('categories.index') }}"
                                class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                Batal
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                            {{ $editing->exists ? 'Simpan perubahan' : 'Tambah kategori' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-7">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar Kategori</h3>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                    Warna dan icon akan membantu pengguna mengenali kategori lebih cepat saat melihat transaksi.
                </p>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Kategori</th>
                                <th class="px-4 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tipe</th>
                                <th class="px-4 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Dipakai</th>
                                <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900"
                                                style="color: {{ $category->color ?: '#465FFF' }}">
                                                {!! MenuHelper::getCategoryIconSvg($category->icon ?? 'tag') !!}
                                            </span>

                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="h-3 w-3 rounded-full"
                                                        style="background-color: {{ $category->color ?: '#465FFF' }}"></span>
                                                    <p class="font-medium text-gray-800 dark:text-white/90">{{ $category->name }}</p>
                                                </div>
                                                <p class="mt-1 text-theme-xs text-gray-500 dark:text-gray-400">
                                                    {{ $category->description ?: 'Tanpa deskripsi' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="rounded-full px-3 py-1 text-theme-xs font-medium {{ $category->type === 'income' ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-error-50 text-error-700 dark:bg-error-500/10 dark:text-error-300' }}">
                                            {{ config('finance.transaction_types.' . $category->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-700 dark:text-gray-300">
                                        {{ $category->transactions_count }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('categories.index', ['edit' => $category->id]) }}"
                                                class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-200 px-3 text-theme-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                                Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Hapus kategori ini?')">
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
                                    <td colspan="4"
                                        class="px-4 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                        Belum ada kategori.
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
