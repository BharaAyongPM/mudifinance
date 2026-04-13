<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BusinessUnit;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;
        $filters = $this->filters($request);
        $query = Transaction::query()
            ->where('user_id', $userId)
            ->posted()
            ->with(['account', 'businessUnit', 'category'])
            ->filter($filters);

        return view('reports.index', [
            'title' => 'Laporan Keuangan',
            'pageTitle' => 'Laporan',
            'filters' => $filters,
            'summary' => $this->summary($query),
            'transactions' => (clone $query)
                ->latest('transaction_date')
                ->latest('id')
                ->paginate(20)
                ->withQueryString(),
            'trend' => $this->trendData($filters, $userId),
            'accounts' => Account::query()->where('user_id', $userId)->orderBy('name')->get(),
            'categories' => Category::query()->where('user_id', $userId)->orderBy('type')->orderBy('name')->get(),
            'businessUnits' => BusinessUnit::query()->where('user_id', $userId)->orderBy('name')->get(),
            'filterLabels' => $this->filterLabels($filters, $userId),
            'accountBreakdown' => $this->accountBreakdown($filters, $userId),
            'categoryBreakdown' => $this->categoryBreakdown($filters, $userId),
            'businessUnitBreakdown' => $this->businessUnitBreakdown($filters, $userId),
        ]);
    }

    public function print(Request $request): View
    {
        $userId = $request->user()->id;
        $filters = $this->filters($request);
        $query = Transaction::query()
            ->where('user_id', $userId)
            ->posted()
            ->with(['account', 'businessUnit', 'category'])
            ->filter($filters);

        return view('reports.print', [
            'title' => 'Cetak Laporan',
            'filters' => $filters,
            'filterLabels' => $this->filterLabels($filters, $userId),
            'summary' => $this->summary($query),
            'transactions' => (clone $query)
                ->latest('transaction_date')
                ->latest('id')
                ->get(),
            'categoryBreakdown' => $this->categoryBreakdown($filters, $userId),
            'businessUnitBreakdown' => $this->businessUnitBreakdown($filters, $userId),
        ]);
    }

    private function filters(Request $request): array
    {
        return [
            'type' => $request->input('type'),
            'account_id' => $request->input('account_id'),
            'category_id' => $request->input('category_id'),
            'business_unit_id' => $request->input('business_unit_id'),
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->endOfMonth()->toDateString()),
        ];
    }

    private function filterLabels(array $filters, int $userId): array
    {
        return [
            'type' => $filters['type'] ? config('finance.transaction_types.' . $filters['type']) : null,
            'account' => $filters['account_id'] ? Account::query()->where('user_id', $userId)->find($filters['account_id'])?->name : null,
            'category' => $filters['category_id'] ? Category::query()->where('user_id', $userId)->find($filters['category_id'])?->name : null,
            'business_unit' => $filters['business_unit_id']
                ? BusinessUnit::query()->where('user_id', $userId)->find($filters['business_unit_id'])?->name
                : null,
        ];
    }

    private function summary($query): array
    {
        $incomeTotal = (clone $query)->where('type', 'income')->sum('amount');
        $expenseTotal = (clone $query)->where('type', 'expense')->sum('amount');

        return [
            'income_total' => $incomeTotal,
            'expense_total' => $expenseTotal,
            'net_total' => $incomeTotal - $expenseTotal,
            'transaction_count' => (clone $query)->count(),
        ];
    }

    private function trendData(array $filters, int $userId): array
    {
        $rows = Transaction::query()
            ->where('user_id', $userId)
            ->posted()
            ->filter($filters)
            ->get(['transaction_date', 'type', 'amount']);

        $start = Carbon::parse($filters['date_from']);
        $end = Carbon::parse($filters['date_to']);
        $groupByMonth = $start->diffInDays($end) > 45;

        if ($groupByMonth) {
            $period = CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->startOfMonth());
            $labels = collect($period)->map(fn (Carbon $date) => $date->translatedFormat('M Y'));

            $income = $labels->map(function (string $label) use ($rows) {
                return (float) $rows
                    ->where('type', 'income')
                    ->filter(fn (Transaction $transaction) => $transaction->transaction_date->translatedFormat('M Y') === $label)
                    ->sum('amount');
            });

            $expense = $labels->map(function (string $label) use ($rows) {
                return (float) $rows
                    ->where('type', 'expense')
                    ->filter(fn (Transaction $transaction) => $transaction->transaction_date->translatedFormat('M Y') === $label)
                    ->sum('amount');
            });
        } else {
            $period = CarbonPeriod::create($start, '1 day', $end);
            $labels = collect($period)->map(fn (Carbon $date) => $date->translatedFormat('d M'));

            $income = $labels->map(function (string $label) use ($rows) {
                return (float) $rows
                    ->where('type', 'income')
                    ->filter(fn (Transaction $transaction) => $transaction->transaction_date->translatedFormat('d M') === $label)
                    ->sum('amount');
            });

            $expense = $labels->map(function (string $label) use ($rows) {
                return (float) $rows
                    ->where('type', 'expense')
                    ->filter(fn (Transaction $transaction) => $transaction->transaction_date->translatedFormat('d M') === $label)
                    ->sum('amount');
            });
        }

        return [
            'labels' => $labels->values()->all(),
            'income' => $income->values()->all(),
            'expense' => $expense->values()->all(),
        ];
    }

    private function categoryBreakdown(array $filters, int $userId)
    {
        return Category::query()
            ->where('user_id', $userId)
            ->withSum([
                'transactions as total_amount' => fn ($query) => $query->where('user_id', $userId)->posted()->filter($filters),
            ], 'amount')
            ->orderByDesc('total_amount')
            ->get()
            ->filter(fn (Category $category) => (float) ($category->total_amount ?? 0) > 0)
            ->values();
    }

    private function accountBreakdown(array $filters, int $userId)
    {
        return Account::query()
            ->where('user_id', $userId)
            ->withSum([
                'transactions as total_amount' => fn ($query) => $query->where('user_id', $userId)->posted()->filter($filters),
            ], 'amount')
            ->orderByDesc('total_amount')
            ->get()
            ->filter(fn (Account $account) => (float) ($account->total_amount ?? 0) > 0)
            ->values();
    }

    private function businessUnitBreakdown(array $filters, int $userId)
    {
        return BusinessUnit::query()
            ->where('user_id', $userId)
            ->withSum([
                'transactions as total_amount' => fn ($query) => $query->where('user_id', $userId)->posted()->filter($filters),
            ], 'amount')
            ->orderByDesc('total_amount')
            ->get()
            ->filter(fn (BusinessUnit $businessUnit) => (float) ($businessUnit->total_amount ?? 0) > 0)
            ->values();
    }
}
