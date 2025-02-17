<?php

namespace Modules\Wallets\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallets\Entities\CommissionWalletTransaction;

class WalletsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        // $this->call("OthersTableSeeder");
        CommissionWalletTransaction::factory()->count(30)->create();
    }
}
