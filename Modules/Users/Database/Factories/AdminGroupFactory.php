<?php

namespace Modules\Users\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Users\App\Models\AdminGroup::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'=>'Full Admin',
        ];
    }
}

