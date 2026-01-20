<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => '7 Day Streak',
                'description' => 'Catat data kesehatan selama 7 hari berturut-turut',
                'icon_url' => '🔥',
                'requirement' => 7,
                'type' => 'streak',
            ],
            [
                'name' => '30 Day Streak',
                'description' => 'Catat data kesehatan selama 30 hari berturut-turut',
                'icon_url' => '⭐',
                'requirement' => 30,
                'type' => 'streak',
            ],
            [
                'name' => '100 Points',
                'description' => 'Kumpulkan 100 poin dari pencatatan data',
                'icon_url' => '🏅',
                'requirement' => 100,
                'type' => 'points',
            ],
            [
                'name' => '500 Points',
                'description' => 'Kumpulkan 500 poin dari pencatatan data',
                'icon_url' => '🏆',
                'requirement' => 500,
                'type' => 'points',
            ],
            [
                'name' => '1000 Points',
                'description' => 'Kumpulkan 1000 poin dari pencatatan data',
                'icon_url' => '👑',
                'requirement' => 1000,
                'type' => 'points',
            ],
            [
                'name' => '10 Entries',
                'description' => 'Catat 10 data kesehatan',
                'icon_url' => '📝',
                'requirement' => 10,
                'type' => 'entries',
            ],
            [
                'name' => '50 Entries',
                'description' => 'Catat 50 data kesehatan',
                'icon_url' => '📊',
                'requirement' => 50,
                'type' => 'entries',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
