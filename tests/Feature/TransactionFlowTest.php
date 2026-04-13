<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\FinanceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_store_a_transaction(): void
    {
        $this->seed(FinanceSeeder::class);

        $user = User::query()->where('email', config('finance.default_admin.email'))->firstOrFail();
        $account = Account::query()->where('user_id', $user->id)->firstOrFail();
        $category = Category::query()->where('user_id', $user->id)->where('type', 'income')->firstOrFail();

        $response = $this->actingAs($user)->post(route('transactions.store'), [
            'transaction_date' => now()->toDateString(),
            'type' => 'income',
            'account_id' => $account->id,
            'category_id' => $category->id,
            'amount' => 150000,
            'reference_number' => 'INV-TEST-001',
            'payment_method' => 'Transfer',
            'counterparty' => 'Pelanggan Test',
            'description' => 'Pembayaran produk digital',
            'status' => 'posted',
        ]);

        $response->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'reference_number' => 'INV-TEST-001',
            'type' => 'income',
            'amount' => 150000,
        ]);
    }
}
