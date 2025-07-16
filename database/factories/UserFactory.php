<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            // name / email **çıkarıldı**
            'username' => $this->faker->unique()->userName,
            'role'     => $this->faker->randomElement(['admin','manager','user']),
            'active'   => true,
            'password' => Hash::make('password'),
        ];
    }
}
