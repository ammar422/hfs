<?php

namespace Modules\Wallets\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Users\App\Models\User;

class CommissionWalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Wallets\Entities\CommissionWallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'balance' => $this->faker->randomNumber(4),
            'user_id' => User::inRandomOrder()->whereAccountType('user')->first()->id
        ];
    }
}
