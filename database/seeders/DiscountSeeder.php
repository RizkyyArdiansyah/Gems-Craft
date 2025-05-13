<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Discount;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Discount::insert([
            [
                'code' => 'DISC30',
                'type' => 'percentage',
                'value' => 30,
                'min_purchase' => 150000,
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'DISC80',
                'type' => 'percentage',
                'value' => 80,
                'min_purchase' => 20000000,
                'expiration_date' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
