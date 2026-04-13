<?php

namespace App\Services;

use App\Helpers\SlugHelper;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;

class UserFinanceProvisioner
{
    public function provision(User $user): void
    {
        foreach (config('finance.default_accounts', []) as $account) {
            $existing = Account::query()
                ->where('user_id', $user->id)
                ->where('name', $account['name'])
                ->first();

            if ($existing) {
                continue;
            }

            Account::query()->create([
                'user_id' => $user->id,
                'name' => $account['name'],
                'slug' => SlugHelper::unique($account['name'], Account::class),
                'type' => $account['type'],
                'opening_balance' => $account['opening_balance'] ?? 0,
                'description' => $account['description'] ?? null,
                'is_active' => true,
            ]);
        }

        foreach (config('finance.default_categories', []) as $category) {
            $existing = Category::query()
                ->where('user_id', $user->id)
                ->where('name', $category['name'])
                ->where('type', $category['type'])
                ->first();

            if ($existing) {
                continue;
            }

            Category::query()->create([
                'user_id' => $user->id,
                'name' => $category['name'],
                'slug' => SlugHelper::unique($category['name'], Category::class),
                'type' => $category['type'],
                'color' => $category['color'] ?? null,
                'icon' => $category['icon'] ?? null,
                'description' => $category['description'] ?? null,
                'is_active' => true,
            ]);
        }
    }
}
