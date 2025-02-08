<?php

namespace Modules\Packages\Database\factories;

use Modules\Users\App\Models\User;
use Modules\Packages\Entities\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Packages\Entities\Package::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $package        = Package::inRandomOrder()->first();


        return [
            'user_id'           => User::inRandomOrder()->first()->id,
            'package_id'        => $package->id,
            'name'              => $package->name,
            'cv'                => $package->cv,
            'billing_period'    => $package->billing_period,
        ];
    }
}
