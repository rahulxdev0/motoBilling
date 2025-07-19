<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'Your Company Name',
            'address' => '123 Business Street',
            'city' => 'City Name',
            'state' => 'State Name',
            'pincode' => '123456',
            'country' => 'India',
            'phone' => '123-456-7890',
            'email' => 'info@yourcompany.com',
            'website' => 'https://yourcompany.com',
            'gstin' => '27AAAAA0000A1Z5',
            'pan' => 'AAAAA0000A',
            'currency' => 'INR',
            'currency_symbol' => 'Rs.',
            'tax_percentage' => 18.00,
            'terms_conditions' => 'Payment is due within 15 days from the date of invoice. Late payments may incur additional charges.',
            'is_active' => true,
        ]);
    }
}
