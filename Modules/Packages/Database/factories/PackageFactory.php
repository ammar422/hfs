<?php

namespace Modules\Packages\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
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
        return [
            'name' => $this->faker->randomElement(['Essential', 'Basic', 'Premium', 'Pro', 'Ultimate']),
            'price' => 0,
            'billing_period' => $this->faker->randomElement(['Monthly', 'Annual', 'Quarterly', 'Biannual', 'Lifelong']),
            'cv' => 0,
        ];
    }
}
