<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserFinanceProvisioner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private readonly UserFinanceProvisioner $provisioner)
    {
    }

    public function index(Request $request): View
    {
        return view('users.index', [
            'title' => 'Manajemen User',
            'pageTitle' => 'Manajemen User',
            'users' => User::query()->orderBy('role')->orderBy('name')->get(),
            'editing' => $request->filled('edit')
                ? User::query()->findOrFail($request->integer('edit'))
                : new User(['role' => 'user', 'is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys(config('finance.roles')))],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->provisioner->provision($user);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(array_keys(config('finance.roles')))],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);
        $this->provisioner->provision($user);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Admin yang sedang login tidak bisa menghapus dirinya sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
