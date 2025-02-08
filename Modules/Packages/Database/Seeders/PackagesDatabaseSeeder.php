<?php

namespace Modules\Packages\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Packages\Entities\Package;

class PackagesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Package::factory()->count(10)->create();
        $packages = [

            [
                'name' => 'Basic-init',
                'price' => 0,
                'billing_period' => 'yearly',
                'cv' => 0,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => false,
                    'Live sessions' => true,
                    'Advance course' => false,
                    'Expert course' => false,
                    'Expert plus course' => false,
                    'Scanners' => false,
                    'Private sessions with selected coach' => false,
                    'Affiliate program' => false,
                    'Affiliate program with extra Bonus' => false,
                ]
            ],

            [
                'name' => 'Basic-init-plus',
                'price' => 0,
                'billing_period' => 'quarterly',
                'cv' => 0,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => false,
                    'Live sessions' => true,
                    'Advance course' => false,
                    'Expert course' => false,
                    'Expert plus course' => false,
                    'Scanners' => false,
                    'Private sessions with selected coach' => false,
                    'Affiliate program' => false,
                    'Affiliate program with extra Bonus' => false,
                ]
            ],

            [
                'name' => 'Premium-init',
                'price' => 0,
                'billing_period' => 'yearly',
                'cv' => 0,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => true,
                    'Live sessions' => true,
                    'Advance course' => true,
                    'Expert course' => false,
                    'Expert plus course' => false,
                    'One Scanners' => true,
                    'Private sessions with selected coach' => false,
                    'Affiliate program' => false,
                    'Affiliate program with extra Bonus' => false,
                ]
            ],


            [
                'name' => 'Pro-init',
                'price' => 0,
                'billing_period' => 'yearly',
                'cv' => 0,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => true,
                    'Live sessions' => true,
                    'Advance course' => true,
                    'Expert course' => true,
                    'Expert plus course' => false,
                    'One Scanners' => true,
                    'Private sessions with selected coach' => true,
                    'Affiliate program' => true,
                    'Affiliate program with extra Bonus' => true,
                ]
            ],

            //demo end

            [
                'name' => 'Essential',
                'price' => 99,
                'billing_period' => 'monthly',
                'cv' => 25,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => true,
                    'Live sessions' => false,
                    'Advance course' => false,
                    'Expert course' => false,
                    'Expert plus course' => false,
                    'Scanners' => false,
                    'Private sessions with selected coach' => false,
                    'Affiliate program' => false,
                    'Affiliate program with extra Bonus' => false,
                ]
            ],
            [
                'name' => 'Basic',
                'price' => 399,
                'billing_period' => 'yearly',
                'cv' => 100,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => false,
                    'Live sessions' => true,
                    'Advance course' => false,
                    'Expert course' => false,
                    'Expert plus course' => false,
                    'Scanners' => false,
                    'Private sessions with selected coach' => false,
                    'Affiliate program' => false,
                    'Affiliate program with extra Bonus' => false,
                ]
            ],
            [
                'name' => 'Premium',
                'price' => 749,
                'billing_period' => 'yearly',
                'cv' => 200,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => true,
                    'Live sessions' => true,
                    'Advance course' => true,
                    'Expert course' => false,
                    'Expert plus course' => false,
                    'One Scanners' => true,
                    'Private sessions with selected coach' => false,
                    'Affiliate program' => false,
                    'Affiliate program with extra Bonus' => false,
                ]
            ],
            [
                'name' => 'Pro',
                'price' => 1499,
                'billing_period' => 'yearly',
                'cv' => 500,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => true,
                    'Live sessions' => true,
                    'Advance course' => true,
                    'Expert course' => true,
                    'Expert plus course' => false,
                    'One Scanners' => true,
                    'Private sessions with selected coach' => true,
                    'Affiliate program' => true,
                    'Affiliate program with extra Bonus' => true,
                ]
            ],
            [
                'name' => 'Ultimate',
                'price' => 1999,
                'billing_period' => 'yearly',
                'cv' => 600,
                'features' => [
                    'Trade alert' => true,
                    'Beginner course' => true,
                    'Basics course' => true,
                    'Live trading' => true,
                    'Live sessions' => true,
                    'Advance course' => true,
                    'Expert course' => true,
                    'Expert plus course' => true,
                    'One Scanners' => true,
                    'Private sessions with selected coach' => true,
                    'Affiliate program' => true,
                    'Affiliate program with extra Bonus' => true,
                ]
            ],




        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
