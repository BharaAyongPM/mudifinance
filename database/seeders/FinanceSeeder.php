<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\BusinessUnit;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Services\UserFinanceProvisioner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FinanceSeeder extends Seeder
{
    public function __construct(
        private readonly UserFinanceProvisioner $provisioner
    ) {
    }

    public function run(): void
    {
        $adminConfig = config('finance.default_admin');

        $admin = User::query()->updateOrCreate(
            ['email' => $adminConfig['email']],
            [
                'name' => $adminConfig['name'],
                'password' => Hash::make($adminConfig['password']),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->provisioner->provision($admin);

        Transaction::query()
            ->whereNull('user_id')
            ->update(['user_id' => $admin->id]);

        Account::query()
            ->whereNull('user_id')
            ->update(['user_id' => $admin->id]);

        Category::query()
            ->whereNull('user_id')
            ->update(['user_id' => $admin->id]);

        $legacyBusinessUnitIds = BusinessUnit::query()
            ->whereNull('user_id')
            ->pluck('id');

        if ($legacyBusinessUnitIds->isNotEmpty()) {
            Transaction::query()
                ->whereIn('business_unit_id', $legacyBusinessUnitIds)
                ->update(['business_unit_id' => null]);

            BusinessUnit::query()
                ->whereIn('id', $legacyBusinessUnitIds)
                ->delete();
        }
    }
}
