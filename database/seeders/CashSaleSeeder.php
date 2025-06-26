<?php

namespace Database\Seeders;

use App\Models\Partie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Partie::firstOrCreate(
            ['name' => 'Cash Sale Customer'],
            [
                'name' => 'Cash Sale Customer',
                'email' => null,
                'phone' => '0000000000',
                'address' => 'Walk-in Customer',
                'contact_person' => 'Cash Sale',
                'gstin' => null,
                'pan' => null,
                'is_active' => true,
            ]
        );
    }
}
