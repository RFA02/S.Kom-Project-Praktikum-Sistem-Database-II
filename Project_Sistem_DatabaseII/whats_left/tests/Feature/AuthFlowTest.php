<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login_and_is_sent_to_explore(): void
    {
        User::factory()->create([
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'role' => 'customer',
        ]);

        $this->post('/login', [
            'email' => 'budi@example.com',
            'password' => 'rahasia123',
            'role' => 'consumer',
        ])->assertRedirect(route('consumer.explore'));

        $this->assertAuthenticated();
    }

    public function test_guest_cannot_open_business_dashboard(): void
    {
        $this->get('/business/dashboard')->assertRedirect(route('login'));
    }

    public function test_customer_cannot_open_business_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)->get('/business/dashboard')->assertStatus(403);
    }

    public function test_forgot_password_page_and_request_work(): void
    {
        $this->get('/forgot-password')->assertStatus(200);

        $this->post('/forgot-password', ['email' => 'tidakada@example.com'])
            ->assertSessionHas('success');
    }

    public function test_only_admin_can_open_admin_dashboard(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($customer)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
    }
}
