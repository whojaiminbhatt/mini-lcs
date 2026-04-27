<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Loan;
use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Field Agent
        $agent = User::create([
            'name' => 'Field Agent',
            'email' => 'agent@agent.com',
            'password' => Hash::make('password'),
            'role' => 'field_agent',
        ]);

        // Create Loans
        $loan1 = Loan::create([
            'loan_no' => 'LN001',
            'customer_name' => 'John Doe',
            'mobile' => '9876543210',
            'address' => '123 Main St, City',
            'total_amount' => 50000,
            'emi_amount' => 5000,
        ]);

        $loan2 = Loan::create([
            'loan_no' => 'LN002',
            'customer_name' => 'Jane Smith',
            'mobile' => '9876543211',
            'address' => '456 Park Ave, City',
            'total_amount' => 100000,
            'emi_amount' => 10000,
        ]);

        // Create Collections
        Collection::create([
            'loan_id' => $loan1->id,
            'amount_paid' => 5000,
            'payment_mode' => 'cash',
            'location' => 'Office',
            'collected_at' => Carbon::now()->subDays(10)->setTime(10, 30),
            'collected_by' => $agent->id,
        ]);

        Collection::create([
            'loan_id' => $loan1->id,
            'amount_paid' => 10000,
            'payment_mode' => 'upi',
            'location' => 'Home',
            'collected_at' => Carbon::now()->subDays(5)->setTime(11, 45),
            'collected_by' => $agent->id,
        ]);

        Collection::create([
            'loan_id' => $loan2->id,
            'amount_paid' => 10000,
            'payment_mode' => 'cash',
            'location' => 'Shop',
            'collected_at' => Carbon::now()->subDays(2)->setTime(14, 15),
            'collected_by' => $agent->id,
        ]);

        Collection::create([
            'loan_id' => $loan2->id,
            'amount_paid' => 10000,
            'payment_mode' => 'card',
            'location' => 'Shop',
            'collected_at' => Carbon::now()->setTime(15, 30),
            'collected_by' => $agent->id,
        ]);
    }
}
