<?php

namespace Modules\Ranks\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Ranks\Entities\Rank;
use Illuminate\Database\Eloquent\Model;

class RanksDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $ranks = [
            [
                'name' => 'Executive',
                'left_volume' => 100,
                'right_volume' => 100,
                'direct_referrals' => 2,
                'downline_requirements' => null,
            ],
            [
                'name' => 'Pearl',
                'left_volume' => 500,
                'right_volume' => 500,
                'direct_referrals' => 2,
                'downline_requirements' => null,
            ],
            [
                'name' => 'Sapphire',
                'left_volume' => 1000,
                'right_volume' => 1000,
                'direct_referrals' => 2,
                'downline_requirements' => null,
            ],
            [
                'name' => 'Ruby',
                'left_volume' => 8000,
                'right_volume' => 8000,
                'direct_referrals' => 2,
                'downline_requirements' => json_encode(['Sapphire' => 1]), // 1 Sapphire per leg
            ],
            [
                'name' => 'Emerald',
                'left_volume' => 20000,
                'right_volume' => 20000,
                'direct_referrals' => 3,
                'downline_requirements' => json_encode(['Ruby' => 1]), // 1 Ruby per leg
            ],
            [
                'name' => 'Diamond',
                'left_volume' => 40000,
                'right_volume' => 40000,
                'direct_referrals' => 5,
                'downline_requirements' => json_encode(['Emerald' => 1]), // 1 Emerald per leg
            ],
            [
                'name' => 'Blue_Diamond',
                'left_volume' => 80000,
                'right_volume' => 80000,
                'direct_referrals' => 6,
                'downline_requirements' => json_encode(['Diamond' => 3, 'min_per_leg' => 1]), // 3 Diamonds, 1 per leg
            ],
            [
                'name' => 'Black_Diamond',
                'left_volume' => 160000,
                'right_volume' => 160000,
                'direct_referrals' => 7,
                'downline_requirements' => json_encode(['Blue Diamond' => 3, 'min_per_leg' => 1]), // 3 Blue Diamonds, 1 per leg
            ],
            [
                'name' => 'Crown',
                'left_volume' => 300000,
                'right_volume' => 300000,
                'direct_referrals' => 8,
                'downline_requirements' => json_encode(['Black Diamond' => 4, 'min_per_leg' => 2]), // 4 Black Diamonds, 2 per leg
            ],
            [
                'name' => 'Presidential_Crown',
                'left_volume' => 500000,
                'right_volume' => 500000,
                'direct_referrals' => 10,
                'downline_requirements' => json_encode(['Crown' => 4, 'min_per_leg' => 2]), // 4 Crowns, 2 per leg
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::create($rank);
        }
    }
}
