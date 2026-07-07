<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Skin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@skinshop.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ],
        );

        Setting::query()->firstOrCreate([], [
            'admin_ml_id' => '123456789',
            'admin_ml_nickname' => 'AdminStore',
            'dana_number' => '081234567890',
            'whatsapp_number' => '6281234567890',
            'current_diamond_balance' => 2000,
        ]);

        $skins = [
            ['name' => 'Lightborn - Striker', 'hero_name' => 'Alucard', 'type' => 'Lightborn', 'price_diamond' => 1089, 'price_rupiah' => 350000],
            ['name' => 'Blazing West', 'hero_name' => 'Granger', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000],
            ['name' => 'Shadow of Obscurity', 'hero_name' => 'Hayabusa', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000],
            ['name' => 'Sakura Wishes', 'hero_name' => 'Kagura', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000],
            ['name' => 'Fleet Warden', 'hero_name' => 'Chou', 'type' => 'Special', 'price_diamond' => 599, 'price_rupiah' => 195000],
            ['name' => 'Starlight Member', 'hero_name' => 'Miya', 'type' => 'Starlight', 'price_diamond' => 431, 'price_rupiah' => 145000],
            ['name' => 'Vengeance Blade', 'hero_name' => 'Saber', 'type' => 'Elite', 'price_diamond' => 299, 'price_rupiah' => 100000],
        ];

        foreach ($skins as $skin) {
            Skin::query()->firstOrCreate(
                ['name' => $skin['name'], 'hero_name' => $skin['hero_name']],
                $skin + ['is_active' => true],
            );
        }
    }
}
