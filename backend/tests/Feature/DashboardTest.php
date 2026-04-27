<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use App\Models\Collection;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_metrics_can_be_retrieved()
    {
        $user = User::factory()->create();
        
        $loan = Loan::factory()->create(['total_amount' => 10000]);
        Collection::factory()->create(['loan_id' => $loan->id, 'amount_paid' => 1000]);

        $response = $this->actingAs($user)->getJson('/api/dashboard');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success', 
                     'message', 
                     'data' => [
                         'total_loans', 
                         'total_collected_today', 
                         'pending_amount', 
                         'collection_by_mode'
                     ]
                 ]);
    }

    public function test_best_collection_time_can_be_retrieved()
    {
        $user = User::factory()->create();
        $loan = Loan::factory()->create();
        
        Collection::factory()->create([
            'loan_id' => $loan->id, 
            'collected_at' => now()->setTime(10, 30)
        ]);

        $response = $this->actingAs($user)->getJson('/api/dashboard/best-time');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'best_slot',
                         'amount_collected',
                         'all_slots'
                     ]
                 ]);
    }

    public function test_dashboard_routes_require_authentication()
    {
        $response = $this->getJson('/api/dashboard');
        $response->assertStatus(401);

        $response2 = $this->getJson('/api/dashboard/best-time');
        $response2->assertStatus(401);
    }
}
