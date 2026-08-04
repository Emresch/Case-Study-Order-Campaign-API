<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campaign::create([
            'title' => '%10 Sepet İndirimi',
            'type' => 'total_percentage',
            'parameters' => [
                'threshold' => 200.00,
                'discount_ratio' => 0.10,
            ],
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);

        Campaign::create([
            'title' => '%15 Roman Kategorisi İndirimi',
            'type' => 'category_percentage',
            'parameters' => [
                'category_id' => 1, // Roman kategorisi
                'discount_ratio' => 0.15, // %15 indirim
            ],
            'start_date' => now(),
            'end_date' => now()->addMonth(),

        ]);
        Campaign::create([
            'title' => 'Ahmet Ümit Kitaplarında 3 Al 2 Öde',
            'type' => 'buy_x_pay_y',
            'parameters' => [
                'author_id' => 3, // Ahmet Ümit (veya Sabahattin Ali)
                'min_quantity' => 3,
            ],
            'start_date' => now(),
            'end_date' => now()->addMonth(),
        ]);
    }
}
