<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Donation::firstOrCreate(['name' => 'small', 'price' => 3.00]);
        Donation::firstOrCreate(['name' => 'medium', 'price' => 10.00]);
        Donation::firstOrCreate(['name' => 'large', 'price' => 25.00]);
    }
}
