<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_date',
        'type',
        'account_id',
        'business_unit_id',
        'category_id',
        'amount',
        'reference_number',
        'payment_method',
        'counterparty',
        'description',
        'receipt_path',
        'receipt_original_name',
        'status',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function scopePosted(Builder $query): Builder
    {
        return $query->where('status', 'posted');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query
                        ->where('reference_number', 'like', '%' . $search . '%')
                        ->orWhere('counterparty', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['account_id'] ?? null, fn (Builder $query, string $accountId) => $query->where('account_id', $accountId))
            ->when($filters['category_id'] ?? null, fn (Builder $query, string $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['business_unit_id'] ?? null, fn (Builder $query, string $businessUnitId) => $query->where('business_unit_id', $businessUnitId))
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $dateFrom) => $query->whereDate('transaction_date', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $dateTo) => $query->whereDate('transaction_date', '<=', $dateTo));
    }
}
