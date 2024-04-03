<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['manager', 'developer', 'design', 'scrum master'];
        $roleName = $this->faker->unique()->randomElement($roles);

        return [
            'name' => $roleName,
        ];
    }

    /**
     * Indicate that the role names should be generated sequentially.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function configure()
    {
        return $this->afterMaking(function (Role $role) {
            static $index = 0;
            $roles = ['manager', 'developer', 'design', 'scrum master'];
            $role->name = $roles[$index];
            $index = ($index + 1) % count($roles);
        });
    }
}
