<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BusinessUnit;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $year = (int) $request->input('year', now()->year);

        $accounts = Account::query()
            ->where('user_id', $user->id)
            ->withSum(['postedTransactions as total_income' => fn ($query) => $query->where('type', 'income')], 'amount')
            ->withSum(['postedTransactions as total_expense' => fn ($query) => $query->where('type', 'expense')], 'amount')
            ->orderBy('name')
            ->get()
            ->map(function (Account $account) {
                $income = (float) ($account->total_income ?? 0);
                $expense = (float) ($account->total_expense ?? 0);
                $openingBalance = (float) $account->opening_balance;
                $account->current_balance = $openingBalance + $income - $expense;

                return $account;
            });

        $cashBalance = $accounts->sum('current_balance');

        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $incomeThisMonth = Transaction::query()
            ->where('user_id', $user->id)
            ->posted()
            ->where('type', 'income')
            ->whereBetween('transaction_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $expenseThisMonth = Transaction::query()
            ->where('user_id', $user->id)
            ->posted()
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $receiptMissingCount = Transaction::query()
            ->where('user_id', $user->id)
            ->posted()
            ->whereNull('receipt_path')
            ->count();

        $draftCount = Transaction::query()
            ->where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();

        $yearTransactions = Transaction::query()
            ->where('user_id', $user->id)
            ->posted()
            ->whereYear('transaction_date', $year)
            ->get(['transaction_date', 'type', 'amount']);

        $monthlyLabels = collect(range(1, 12))
            ->map(fn (int $month) => Carbon::create($year, $month, 1)->translatedFormat('M'))
            ->all();

        $monthlyIncome = collect(range(1, 12))
            ->map(function (int $month) use ($yearTransactions) {
                return (float) $yearTransactions
                    ->where('type', 'income')
                    ->filter(fn (Transaction $transaction) => $transaction->transaction_date->month === $month)
                    ->sum('amount');
            })
            ->all();

        $monthlyExpense = collect(range(1, 12))
            ->map(function (int $month) use ($yearTransactions) {
                return (float) $yearTransactions
                    ->where('type', 'expense')
                    ->filter(fn (Transaction $transaction) => $transaction->transaction_date->month === $month)
                    ->sum('amount');
            })
            ->all();

        $expenseBreakdown = Category::query()
            ->where('user_id', $user->id)
            ->where('type', 'expense')
            ->withSum([
                'transactions as total_amount' => fn ($query) => $query
                    ->posted()
                    ->where('user_id', $user->id)
                    ->whereYear('transaction_date', $year),
            ], 'amount')
            ->orderByDesc('total_amount')
            ->get()
            ->filter(fn (Category $category) => (float) ($category->total_amount ?? 0) > 0)
            ->take(6)
            ->values();

        $businessUnits = BusinessUnit::query()
            ->where('user_id', $user->id)
            ->withSum([
                'transactions as total_income' => fn ($query) => $query
                    ->posted()
                    ->where('user_id', $user->id)
                    ->where('type', 'income')
                    ->whereYear('transaction_date', $year),
            ], 'amount')
            ->withSum([
                'transactions as total_expense' => fn ($query) => $query
                    ->posted()
                    ->where('user_id', $user->id)
                    ->where('type', 'expense')
                    ->whereYear('transaction_date', $year),
            ], 'amount')
            ->get()
            ->map(function (BusinessUnit $businessUnit) {
                $businessUnit->net_total = (float) ($businessUnit->total_income ?? 0) - (float) ($businessUnit->total_expense ?? 0);

                return $businessUnit;
            })
            ->sortByDesc('net_total')
            ->values();

        $recentTransactions = Transaction::query()
            ->where('user_id', $user->id)
            ->with(['account', 'businessUnit', 'category'])
            ->latest('transaction_date')
            ->latest('id')
            ->take(8)
            ->get();

        return view('dashboard.index', [
            'title' => 'Dashboard Keuangan',
            'pageTitle' => 'Dashboard',
            'year' => $year,
            'yearOptions' => range(now()->year - 3, now()->year + 1),
            'cashBalance' => $cashBalance,
            'incomeThisMonth' => $incomeThisMonth,
            'expenseThisMonth' => $expenseThisMonth,
            'netThisMonth' => $incomeThisMonth - $expenseThisMonth,
            'receiptMissingCount' => $receiptMissingCount,
            'draftCount' => $draftCount,
            'accounts' => $accounts,
            'monthlyLabels' => $monthlyLabels,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpense' => $monthlyExpense,
            'expenseBreakdown' => $expenseBreakdown,
            'businessUnits' => $businessUnits,
            'recentTransactions' => $recentTransactions,
            'currentUser' => $user,
        ]);
    }
}
