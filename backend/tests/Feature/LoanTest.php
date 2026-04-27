<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_loan()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson('/api/loans', [
            'loan_no' => 'LN999',
            'customer_name' => 'Alice Doe',
            'mobile' => '1234567890',
            'address' => '123 Main St',
            'total_amount' => 50000,
            'emi_amount' => 5000,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseHas('loans', ['loan_no' => 'LN999']);
    }

    public function test_field_agent_cannot_create_loan()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);

        $response = $this->actingAs($agent)->postJson('/api/loans', [
            'loan_no' => 'LN888',
            'customer_name' => 'Bob Doe',
            'mobile' => '0987654321',
            'address' => '456 Side St',
            'total_amount' => 10000,
            'emi_amount' => 1000,
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_list_loans()
    {
        $user = User::factory()->create();
        Loan::factory()->count(5)->create();

        $response = $this->actingAs($user)->getJson('/api/loans');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['data']]);
    }
}
