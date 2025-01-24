<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::create([
            'code' => 'DISCOUNT2025',
            'discount_type' => 'percentage',
            'discount_amount' => 10, // 10%
            'usage_limit' => 100,
            'used_count' => 0,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(30),
            'status' => true,
        ]);

        Coupon::create([
            'code' => 'FLAT50',
            'discount_type' => 'fixed',
            'discount_amount' => 50, // $50 off
            'usage_limit' => 10,
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(7),
            'status' => true,
        ]);
    }
}
