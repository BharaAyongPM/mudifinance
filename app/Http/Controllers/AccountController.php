<?php

namespace App\Http\Controllers;

use App\Helpers\SlugHelper;
use App\Models\Account;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $accounts = Account::query()
            ->where('user_id', $userId)
            ->withCount('transactions')
            ->withSum(['postedTransactions as total_income' => fn ($query) => $query->where('type', 'income')], 'amount')
            ->withSum(['postedTransactions as total_expense' => fn ($query) => $query->where('type', 'expense')], 'amount')
            ->orderBy('name')
            ->get()
            ->map(function (Account $account) {
                $account->current_balance = (float) $account->opening_balance
                    + (float) ($account->total_income ?? 0)
                    - (float) ($account->total_expense ?? 0);

                return $account;
            });

        return view('accounts.index', [
            'title' => 'Akun Kas',
            'pageTitle' => 'Akun Kas',
            'accounts' => $accounts,
            'editing' => $request->filled('edit')
                ? Account::query()->where('user_id', $userId)->findOrFail($request->integer('edit'))
                : new Account(['is_active' => true, 'type' => 'cash']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = SlugHelper::unique($data['name'], Account::class);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['user_id'] = $request->user()->id;

        Account::query()->create($data);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun kas berhasil ditambahkan.');
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        abort_unless($account->user_id === $request->user()->id, 403);

        $data = $this->validatedData($request, $account);
        $data['slug'] = SlugHelper::unique($data['name'], Account::class, $account->id);
        $data['is_active'] = $request->boolean('is_active');

        $account->update($data);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun kas berhasil diperbarui.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        abort_unless($account->user_id === request()->user()->id, 403);

        try {
            if ($account->transactions()->exists()) {
                return redirect()
                    ->route('accounts.index')
                    ->with('error', 'Akun tidak bisa dihapus karena sudah dipakai transaksi.');
            }

            $account->delete();
        } catch (QueryException) {
            return redirect()
                ->route('accounts.index')
                ->with('error', 'Akun gagal dihapus. Pastikan tidak ada relasi data lain.');
        }

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Akun kas berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Account $account = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('accounts', 'name')
                    ->where(fn ($query) => $query->where('user_id', $request->user()->id))
                    ->ignore($account),
            ],
            'type' => ['required', Rule::in(array_keys(config('finance.account_types')))],
            'account_number' => ['nullable', 'string', 'max:255'],
            'opening_balance' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
