<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        Field::updateOrCreate(
            ['name' => 'Court A - Panoramic Glass'],
            [
                'type' => 'indoor',
                'price_per_hour' => 250000,
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=800&auto=format&fit=crop'
            ]
        );

        Field::updateOrCreate(
            ['name' => 'Court B - Pro Center Court'],
            [
                'type' => 'outdoor',
                'price_per_hour' => 200000,
                'is_active' => true,
                'image' => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?q=80&w=800&auto=format&fit=crop'
            ]
        );
    }
}
