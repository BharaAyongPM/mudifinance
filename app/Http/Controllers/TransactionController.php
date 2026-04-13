<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BusinessUnit;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;
        $filters = $request->only([
            'q',
            'type',
            'status',
            'account_id',
            'category_id',
            'business_unit_id',
            'date_from',
            'date_to',
        ]);

        $baseQuery = Transaction::query()
            ->where('user_id', $userId)
            ->filter($filters);

        $transactions = (clone $baseQuery)
            ->with(['account', 'businessUnit', 'category'])
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('transactions.index', [
            'title' => 'Transaksi Keuangan',
            'pageTitle' => 'Transaksi',
            'transactions' => $transactions,
            'filters' => $filters,
            'accounts' => Account::query()->where('user_id', $userId)->orderBy('name')->get(),
            'categories' => Category::query()->where('user_id', $userId)->orderBy('type')->orderBy('name')->get(),
            'businessUnits' => BusinessUnit::query()->where('user_id', $userId)->orderBy('name')->get(),
            'summary' => [
                'income_total' => (clone $baseQuery)->where('type', 'income')->sum('amount'),
                'expense_total' => (clone $baseQuery)->where('type', 'expense')->sum('amount'),
                'draft_count' => (clone $baseQuery)->where('status', 'draft')->count(),
                'receipt_count' => (clone $baseQuery)->whereNotNull('receipt_path')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        $userId = request()->user()->id;

        return view('transactions.form', [
            'title' => 'Tambah Transaksi',
            'pageTitle' => 'Tambah Transaksi',
            'transaction' => new Transaction([
                'transaction_date' => now()->toDateString(),
                'type' => 'income',
                'status' => 'posted',
            ]),
            'accounts' => Account::query()->where('user_id', $userId)->where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::query()->where('user_id', $userId)->where('is_active', true)->orderBy('type')->orderBy('name')->get(),
            'businessUnits' => BusinessUnit::query()->where('user_id', $userId)->where('is_active', true)->orderBy('name')->get(),
            'isEditing' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('receipt')) {
            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            $data['receipt_original_name'] = $request->file('receipt')->getClientOriginalName();
        }

        Transaction::query()->create($data);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit(Transaction $transaction): View
    {
        abort_unless($transaction->user_id === request()->user()->id, 403);
        $userId = request()->user()->id;

        return view('transactions.form', [
            'title' => 'Edit Transaksi',
            'pageTitle' => 'Edit Transaksi',
            'transaction' => $transaction,
            'accounts' => Account::query()->where('user_id', $userId)->where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::query()->where('user_id', $userId)->where('is_active', true)->orderBy('type')->orderBy('name')->get(),
            'businessUnits' => BusinessUnit::query()->where('user_id', $userId)->where('is_active', true)->orderBy('name')->get(),
            'isEditing' => true,
        ]);
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $data = $this->validatedData($request);

        if ($request->hasFile('receipt')) {
            if ($transaction->receipt_path) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }

            $data['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
            $data['receipt_original_name'] = $request->file('receipt')->getClientOriginalName();
        }

        $transaction->update($data);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        abort_unless($transaction->user_id === request()->user()->id, 403);

        if ($transaction->receipt_path) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'transaction_date' => ['required', 'date'],
            'type' => ['required', Rule::in(array_keys(config('finance.transaction_types')))],
            'account_id' => [
                'required',
                Rule::exists('accounts', 'id')->where(
                    fn ($query) => $query->where('user_id', $request->user()->id)
                ),
            ],
            'business_unit_id' => [
                'nullable',
                Rule::exists('business_units', 'id')->where(
                    fn ($query) => $query->where('user_id', $request->user()->id)
                ),
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(
                    fn ($query) => $query
                        ->where('user_id', $request->user()->id)
                        ->where('type', $request->input('type'))
                ),
            ],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'counterparty' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(array_keys(config('finance.transaction_statuses')))],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:4096'],
        ]);
    }
}
