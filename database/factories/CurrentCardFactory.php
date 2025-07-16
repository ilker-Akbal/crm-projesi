<?php
namespace Database\Factories;

use App\Models\CurrentCard;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrentCardFactory extends Factory
{
    protected $model = CurrentCard::class;

    public function definition(): array
    {
        return [
            'customer_id'  => Customer::factory(),
            'balance'      => $this->faker->randomFloat(2, -5000, 5000),
            'opening_date' => $this->faker->date(),
            'updated_by'   => User::factory(),
        ];
    }
}
