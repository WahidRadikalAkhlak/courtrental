<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Field;
use App\Models\Racket;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@padel.com'],
            [
                'name'     => 'Super Admin Padel',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Data Lapangan
        $courtA = Field::updateOrCreate(
            ['name' => 'Court A - Panoramic Glass'],
            [
                'type'           => 'indoor',
                'price_per_hour' => 250000,
                'is_active'      => true,
                'image'          => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?q=80&w=800&auto=format&fit=crop',
            ]
        );

        $courtB = Field::updateOrCreate(
            ['name' => 'Court B - Pro Center Court'],
            [
                'type'           => 'outdoor',
                'price_per_hour' => 200000,
                'is_active'      => true,
                'image'          => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?q=80&w=800&auto=format&fit=crop',
            ]
        );

        // 3. Sewa Raket
        Racket::updateOrCreate(
            ['name' => 'Babolat Counter Vertuo Pro'],
            [
                'brand'             => 'Babolat (Sewa Raket)',
                'price_per_session' => 35000,
                'stock'             => 12,
                'image'             => 'https://images.unsplash.com/photo-1617083934555-ac7d4fedc6b6?q=80&w=400&auto=format&fit=crop',
                'is_active'         => true,
            ]
        );

        Racket::updateOrCreate(
            ['name' => 'Head Evo Sanyo Grip'],
            [
                'brand'             => 'Head (Sewa Raket)',
                'price_per_session' => 30000,
                'stock'             => 10,
                'image'             => 'https://images.unsplash.com/photo-1599474924187-334a4ae5bd3c?q=80&w=400&auto=format&fit=crop',
                'is_active'         => true,
            ]
        );

        // 4. Pembelian Bola Padel Baru (Per Tabung / Can isi 3)
        Racket::updateOrCreate(
            ['name' => 'Head Padel Pro S (Tabung isi 3)'],
            [
                'brand'             => 'Beli Baru (Milik Sendiri)',
                'price_per_session' => 110000,
                'stock'             => 24,
                'image'             => 'https://images.unsplash.com/photo-1587280501635-68a0e82cd5ff?q=80&w=400&auto=format&fit=crop',
                'is_active'         => true,
            ]
        );

        Racket::updateOrCreate(
            ['name' => 'Babolat Padel Court (Tabung isi 3)'],
            [
                'brand'             => 'Beli Baru (Milik Sendiri)',
                'price_per_session' => 95000,
                'stock'             => 18,
                'image'             => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=400&auto=format&fit=crop',
                'is_active'         => true,
            ]
        );

        // 5. Ulasan Awal Pengguna
        if (Review::count() === 0 && $courtA) {
            Review::create([
                'field_id'      => $courtA->id,
                'customer_name' => 'Rahmat Hidayat',
                'rating'        => 5,
                'comment'       => 'Kualitas karpet turf sangat empuk, AC indoor dingin, dan pencahayaan malam mirip arena WPT!',
            ]);
            Review::create([
                'field_id'      => $courtA->id,
                'customer_name' => 'Fadel Muhammad',
                'rating'        => 5,
                'comment'       => 'Pilihan sewa raketnya lengkap dan bola barunya membal dengan sempurna. Sangat rekomendasi di Palu!',
            ]);
        }
    }
}
