<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use App\Models\Collection;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_field_agent_can_add_collection()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $loan = Loan::factory()->create(['total_amount' => 10000]);

        $response = $this->actingAs($agent)->postJson('/api/collections', [
            'loan_no' => $loan->loan_no,
            'amount_paid' => 2000,
            'payment_mode' => 'cash',
            'collected_at' => now()->toDateTimeString(),
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseHas('collections', [
            'loan_id' => $loan->id,
            'amount_paid' => 2000
        ]);
    }

    public function test_admin_cannot_add_collection()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $loan = Loan::factory()->create(['total_amount' => 10000]);

        $response = $this->actingAs($admin)->postJson('/api/collections', [
            'loan_no' => $loan->loan_no,
            'amount_paid' => 2000,
            'payment_mode' => 'cash',
            'collected_at' => now()->toDateTimeString(),
        ]);

        $response->assertStatus(403);
    }

    public function test_cannot_collect_more_than_pending_amount()
    {
        $agent = User::factory()->create(['role' => 'field_agent']);
        $loan = Loan::factory()->create(['total_amount' => 5000]);
        
        Collection::factory()->create([
            'loan_id' => $loan->id,
            'amount_paid' => 3000
        ]);

        // Pending amount is 2000. Trying to collect 3000 should fail.
        $response = $this->actingAs($agent)->postJson('/api/collections', [
            'loan_no' => $loan->loan_no,
            'amount_paid' => 3000,
            'payment_mode' => 'cash',
            'collected_at' => now()->toDateTimeString(),
        ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
    }
}
