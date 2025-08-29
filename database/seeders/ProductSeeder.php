<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Banner Print',
                'description' => 'High quality banner printing',
                'price' => 150000,
                'allows_custom_size' => true,
                'size_presets' => json_encode([
                    ['width' => 100, 'height' => 200],
                    ['width' => 200, 'height' => 300],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Card',
                'description' => 'Standard size business card printing',
                'price' => 50000,
                'allows_custom_size' => false,
                'size_presets' => json_encode([
                    ['width' => 9, 'height' => 5.5],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Poster',
                'description' => 'Poster printing with vibrant colors',
                'price' => 100000,
                'allows_custom_size' => true,
                'size_presets' => json_encode([
                    ['width' => 30, 'height' => 40],
                    ['width' => 50, 'height' => 70],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
