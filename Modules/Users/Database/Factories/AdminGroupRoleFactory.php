<?php

namespace Modules\Users\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdminGroupRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Users\App\Models\AdminGroupRole::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'create' => 'yes',
            'update' => 'yes',
            'show' => 'yes',
            'force_delete' => 'yes',
            'delete' => 'yes',
            'restore' => 'yes',
        ];
    }
}
