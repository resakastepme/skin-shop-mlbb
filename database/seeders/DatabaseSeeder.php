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

        // Real skins whose splash art has been downloaded to storage/app/public/skins.
        $skins = [
            ['name' => 'Lightborn - Striker', 'hero_name' => 'Alucard', 'type' => 'Lightborn', 'price_diamond' => 1089, 'price_rupiah' => 350000, 'image_url' => 'skins/seed-alucard-lightborn-striker.jpg'],
            ['name' => 'Starfall Knight', 'hero_name' => 'Granger', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-granger-starfall-knight.jpg'],
            ['name' => 'Biological Weapon', 'hero_name' => 'Hayabusa', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-hayabusa-biological-weapon.jpg'],
            ['name' => 'Summer Festival', 'hero_name' => 'Kagura', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-kagura-summer-festival.jpg'],
            ['name' => 'Dragon Boy', 'hero_name' => 'Chou', 'type' => 'Special', 'price_diamond' => 599, 'price_rupiah' => 195000, 'image_url' => 'skins/seed-chou-dragon-boy.jpg'],
            ['name' => 'Captain Thorns', 'hero_name' => 'Miya', 'type' => 'Starlight', 'price_diamond' => 431, 'price_rupiah' => 145000, 'image_url' => 'skins/seed-miya-captain-thorns.jpg'],
            ['name' => 'Wandering Sword', 'hero_name' => 'Saber', 'type' => 'Elite', 'price_diamond' => 299, 'price_rupiah' => 100000, 'image_url' => 'skins/seed-saber-wandering-sword.jpg'],
            ['name' => 'Cosmic Gleam', 'hero_name' => 'Gusion', 'type' => 'Legend', 'price_diamond' => 1289, 'price_rupiah' => 415000, 'image_url' => 'skins/seed-gusion-cosmic-gleam.jpg'],
            ['name' => 'Hairstylist', 'hero_name' => 'Gusion', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-gusion-hairstylist.jpg'],
            ['name' => 'Dangerous Liaison', 'hero_name' => 'Gusion', 'type' => 'Starlight', 'price_diamond' => 431, 'price_rupiah' => 145000, 'image_url' => 'skins/seed-gusion-dangerous-liaison.jpg'],
            ['name' => 'Lightborn - Ranger', 'hero_name' => 'Fanny', 'type' => 'Lightborn', 'price_diamond' => 1089, 'price_rupiah' => 350000, 'image_url' => 'skins/seed-fanny-lightborn-ranger.jpg'],
            ['name' => 'Blade of Kibou', 'hero_name' => 'Fanny', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-fanny-blade-of-kibou.jpg'],
            ['name' => 'Punk Princess', 'hero_name' => 'Fanny', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-fanny-punk-princess.jpg'],
            ['name' => 'Royal Matador', 'hero_name' => 'Lancelot', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-lancelot-royal-matador.jpg'],
            ['name' => 'Swordmaster', 'hero_name' => 'Lancelot', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-lancelot-swordmaster.jpg'],
            ['name' => 'Floral Knight', 'hero_name' => 'Lancelot', 'type' => 'Starlight', 'price_diamond' => 431, 'price_rupiah' => 145000, 'image_url' => 'skins/seed-lancelot-floral-knight.jpg'],
            ['name' => 'Street Punk', 'hero_name' => 'Ling', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-ling-street-punk.jpg'],
            ['name' => 'Cyan Finch', 'hero_name' => 'Ling', 'type' => 'Elite', 'price_diamond' => 599, 'price_rupiah' => 195000, 'image_url' => 'skins/seed-ling-cyan-finch.jpg'],
            ['name' => 'Cannon and Roses', 'hero_name' => 'Layla', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-layla-cannon-and-roses.jpg'],
            ['name' => 'Bunny Babe', 'hero_name' => 'Layla', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-layla-bunny-babe.jpg'],
            ['name' => 'Lightborn - Defender', 'hero_name' => 'Tigreal', 'type' => 'Lightborn', 'price_diamond' => 1089, 'price_rupiah' => 350000, 'image_url' => 'skins/seed-tigreal-lightborn-defender.jpg'],
            ['name' => 'Fallen Guard', 'hero_name' => 'Tigreal', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-tigreal-fallen-guard.jpg'],
            ['name' => 'Angelic Agent', 'hero_name' => 'Lesley', 'type' => 'Legend', 'price_diamond' => 1289, 'price_rupiah' => 415000, 'image_url' => 'skins/seed-lesley-angelic-agent.jpg'],
            ['name' => 'Stellaris Ghost', 'hero_name' => 'Lesley', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-lesley-stellaris-ghost.jpg'],
            ['name' => 'Spider Lily', 'hero_name' => 'Karina', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-karina-spider-lily.jpg'],
            ['name' => 'Black Pearl', 'hero_name' => 'Karina', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-karina-black-pearl.jpg'],
            ['name' => 'Changbanpo Commander', 'hero_name' => 'Zilong', 'type' => 'Special', 'price_diamond' => 749, 'price_rupiah' => 240000, 'image_url' => 'skins/seed-zilong-changbanpo-commander.jpg'],
            ['name' => 'Valhalla Ruler', 'hero_name' => 'Franco', 'type' => 'Epic', 'price_diamond' => 899, 'price_rupiah' => 290000, 'image_url' => 'skins/seed-franco-valhalla-ruler.jpg'],
        ];

        foreach ($skins as $skin) {
            Skin::query()->firstOrCreate(
                ['name' => $skin['name'], 'hero_name' => $skin['hero_name']],
                $skin + ['is_active' => true],
            );
        }
    }
}
