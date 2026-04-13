<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\FinanceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke aplikasi');
    }

    public function test_admin_can_login_with_seeded_credentials(): void
    {
        $this->seed(FinanceSeeder::class);

        $response = $this->post(route('login.store'), [
            'email' => config('finance.default_admin.email'),
            'password' => config('finance.default_admin.password'),
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_page_is_forbidden_for_regular_user(): void
    {
        $this->seed(FinanceSeeder::class);

        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();
    }
}
