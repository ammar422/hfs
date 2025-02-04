<?php

namespace Modules\Wallets\Database\factories;

use Modules\Users\App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TokenWalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Wallets\Entities\TokenWallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'balance' => $this->faker->randomNumber(4),
            'user_id' => User::inRandomOrder()->where('account_type', 'user')->first()->id
        ];
    }
}
