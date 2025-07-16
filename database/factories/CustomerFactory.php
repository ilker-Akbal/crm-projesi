<?php
namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->company,
            'customer_type' => $this->faker->randomElement(['candidate','customer','supplier']),
            'phone'         => $this->faker->phoneNumber,
            'email'         => $this->faker->unique()->safeEmail,
            'address'       => $this->faker->address,
            'created_by'    => User::factory(),
            'updated_by'    => User::factory(),
        ];
    }
}
