@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xl:col-span-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $editing->exists ? 'Edit User' : 'Tambah User' }}
                </h3>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                    Admin dapat membuat akun baru dan menentukan role admin atau user.
                </p>

                <form action="{{ $editing->exists ? route('users.update', $editing) : route('users.store') }}" method="POST"
                    class="mt-5 space-y-4">
                    @csrf
                    @if ($editing->exists)
                        @method('PUT')
                    @endif

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $editing->name) }}"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        @error('name')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" value="{{ old('email', $editing->email) }}"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        @error('email')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                        <select name="role"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90">
                            @foreach (config('finance.roles') as $key => $label)
                                <option value="{{ $key }}" @selected(old('role', $editing->role) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">
                            Password {{ $editing->exists ? '(isi jika ingin ganti)' : '' }}
                        </label>
                        <input type="password" name="password"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                        @error('password')
                            <p class="mt-2 text-theme-xs text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-theme-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="h-11 w-full rounded-lg border border-gray-200 bg-transparent px-4 text-theme-sm text-gray-800 focus:border-brand-300 focus:outline-hidden dark:border-gray-800 dark:text-white/90" />
                    </div>

                    <label class="flex items-center gap-3 text-theme-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editing->is_active ?? true))
                            class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500" />
                        User aktif
                    </label>

                    <div class="flex gap-3">
                        @if ($editing->exists)
                            <a href="{{ route('users.index') }}"
                                class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-200 px-4 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                Batal
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-brand-500 px-5 text-theme-sm font-medium text-white hover:bg-brand-600">
                            {{ $editing->exists ? 'Simpan perubahan' : 'Tambah user' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Daftar User</h3>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                    Setiap user baru otomatis mendapatkan akun kas dan kategori default yang generik.
                </p>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="px-4 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">User</th>
                                <th class="px-4 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Role</th>
                                <th class="px-4 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                                <th class="px-4 py-3 text-right text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-gray-100 last:border-0 dark:border-gray-800">
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $user->name }}</p>
                                        <p class="text-theme-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="rounded-full px-3 py-1 text-theme-xs font-medium {{ $user->role === 'admin' ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-300' : 'bg-gray-100 text-gray-700 dark:bg-white/[0.05] dark:text-gray-300' }}">
                                            {{ config('finance.roles.' . $user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span
                                            class="rounded-full px-3 py-1 text-theme-xs font-medium {{ $user->is_active ? 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-300' : 'bg-error-50 text-error-700 dark:bg-error-500/10 dark:text-error-300' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('users.index', ['edit' => $user->id]) }}"
                                                class="inline-flex h-9 items-center justify-center rounded-lg border border-gray-200 px-3 text-theme-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                                                Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                onsubmit="return confirm('Hapus user ini?')">
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
                                    <td colspan="4" class="px-4 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400">
                                        Belum ada user.
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
