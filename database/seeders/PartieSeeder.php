<?php

namespace Database\Seeders;

use App\Models\Partie;
use Illuminate\Database\Seeder;

class PartieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parties = [
            [
                'name' => 'ABC Electronics Pvt Ltd',
                'email' => 'contact@abcelectronics.com',
                'phone' => '+91 9876543210',
                'address' => '123 Electronics Street, Mumbai, Maharashtra 400001',
                'contact_person' => 'Rajesh Kumar',
                'gstin' => '27AABCU9603R1ZX',
                'pan' => 'AABCU9603R',
                'is_active' => true,
            ],
            [
                'name' => 'Tech Solutions India',
                'email' => 'info@techsolutions.co.in',
                'phone' => '+91 8765432109',
                'address' => '456 Tech Park, Bangalore, Karnataka 560001',
                'contact_person' => 'Priya Sharma',
                'gstin' => '29AABCT1234L1ZY',
                'pan' => 'AABCT1234L',
                'is_active' => true,
            ],
            [
                'name' => 'Global Traders Co.',
                'email' => 'sales@globaltraders.in',
                'phone' => '+91 7654321098',
                'address' => '789 Trade Center, Delhi, NCR 110001',
                'contact_person' => 'Amit Patel',
                'gstin' => '07AABCG5678K1ZZ',
                'pan' => 'AABCG5678K',
                'is_active' => false,
            ],
            [
                'name' => 'Metro Supplies',
                'email' => 'orders@metrosupplies.com',
                'phone' => '+91 6543210987',
                'address' => '321 Supply Chain Road, Chennai, Tamil Nadu 600001',
                'contact_person' => 'Sunita Reddy',
                'gstin' => '33AABCM9876M1ZA',
                'pan' => 'AABCM9876M',
                'is_active' => true,
            ],
            [
                'name' => 'Quick Electronics',
                'email' => null,
                'phone' => '+91 5432109876',
                'address' => '654 Electronics Hub, Pune, Maharashtra 411001',
                'contact_person' => 'Vikram Singh',
                'gstin' => null,
                'pan' => null,
                'is_active' => true,
            ],
        ];

        foreach ($parties as $party) {
            Partie::create($party);
        }
    }
}
