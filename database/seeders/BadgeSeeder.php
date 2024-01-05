<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'Beginner',
                'numberOfAchivment' => 0,
            ],
            [
                'name' => 'Intermediate',
                'numberOfAchivment' => 4,
            ],
            [
                'name' => 'Advanced',
                'numberOfAchivment' => 8,
            ],
            [
                'name' => 'Master',
                'numberOfAchivment' => 10,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
