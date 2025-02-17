<?php

namespace Modules\Wallets\Database\factories;

use Modules\Users\App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Wallets\Entities\CommissionWallet;
use Modules\Wallets\Entities\TokenWallet;

class CommissionWalletTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Wallets\Entities\CommissionWalletTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount'                => $this->faker->randomFloat(2, 10, 1000),
            'user_id'               => User::inRandomOrder()->first()->id,
            'commission_wallet_id'  => $this->faker->randomElement([null, CommissionWallet::inRandomOrder()->first()->id]),
            'token_wallet_id'       => $this->faker->randomElement([null, TokenWallet::inRandomOrder()->first()->id]),
            'status'                => $this->faker->randomElement(['pending', 'done', 'rejected']),
            'rejected_reasons'      => $this->faker->optional()->sentence,
            'paid_at'               => $this->faker->optional()->dateTime,
            'transaction_type'      => $this->faker->randomElement(['credit', 'debit', 'cach', 'vodafon_cash', 'to_token_wallet', 'visa', 'commission_transaction']),
            'transaction_fees'      => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
