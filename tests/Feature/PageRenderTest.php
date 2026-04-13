<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\FinanceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_transactions_page_renders_successfully(): void
    {
        $this->seed(FinanceSeeder::class);
        $user = User::query()->where('email', config('finance.default_admin.email'))->firstOrFail();

        $this->actingAs($user)->get(route('transactions.index'))
            ->assertOk()
            ->assertSee('Daftar Transaksi');
    }

    public function test_reports_page_renders_successfully(): void
    {
        $this->seed(FinanceSeeder::class);
        $user = User::query()->where('email', config('finance.default_admin.email'))->firstOrFail();

        $this->actingAs($user)->get(route('reports.index'))
            ->assertOk()
            ->assertSee('Filter Laporan');
    }

    public function test_accounts_page_renders_successfully(): void
    {
        $this->seed(FinanceSeeder::class);
        $user = User::query()->where('email', config('finance.default_admin.email'))->firstOrFail();

        $this->actingAs($user)->get(route('accounts.index'))
            ->assertOk()
            ->assertSee('Daftar Akun');
    }

    public function test_categories_page_renders_successfully(): void
    {
        $this->seed(FinanceSeeder::class);
        $user = User::query()->where('email', config('finance.default_admin.email'))->firstOrFail();

        $this->actingAs($user)->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Daftar Kategori');
    }

    public function test_business_units_page_renders_successfully(): void
    {
        $this->seed(FinanceSeeder::class);
        $user = User::query()->where('email', config('finance.default_admin.email'))->firstOrFail();

        $this->actingAs($user)->get(route('business-units.index'))
            ->assertOk()
            ->assertSee('Daftar Unit Bisnis');
    }
}
