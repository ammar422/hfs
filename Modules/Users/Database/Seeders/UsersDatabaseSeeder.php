<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Countries\App\Models\Country;
use Modules\Packages\Entities\Package;
use Modules\Packages\Entities\Subscription;
use Modules\Users\App\Models\AdminGroup;
use Modules\Users\App\Models\AdminGroupRole;
use Modules\Users\App\Models\User;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);


        if (User::where('email', 'test@test.com')->count() == 0) {
            $group = AdminGroup::create(['name' => 'full admin']);



            User::factory()->create([
                'full_name'         => 'admin',
                'email'             => 'test@test.com',
                'first_name'        => 'test',
                'last_name'         => 'test',
                'password'          => bcrypt((string) 123456),
                'account_type'      => 'admin',
                'admin_group_id'    => $group->id,
                'email_verified_at' => now(),
            ]);

            User::factory()->create([
                'full_name'         => 'user',
                'email'             => 'u@test.com',
                'first_name'        => 'user',
                'last_name'         => 'user',
                'account_status'    => 'active',
                'password'          => bcrypt((string) 123456),
                'account_type'      => 'user',
                'admin_group_id'    => $group->id,
                'email_verified_at' => now(),
            ]);
        }

        User::factory()->count(10)->create();
        $users =  User::where('account_type', 'user')->get();
        // $users =  User::get();
        foreach ($users as $user) {
            $packege = Package::where('cv', '!=', 0)->inRandomOrder()->first();
            Subscription::create([
                'user_id' => $user->id,
                'package_id' => $packege->id,
                'name' => $packege->name,
                'cv' => $packege->cv,
                'billing_period' => $packege->billing_period,
            ]);
        }
    }
}
