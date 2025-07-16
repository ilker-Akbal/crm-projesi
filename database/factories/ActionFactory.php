<?php
namespace Database\Factories;

use App\Models\Action;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionFactory extends Factory
{
    protected $model = Action::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'user_id'     => User::factory(),
            'action_type' => $this->faker->randomElement(['telefon','mail','ziyaret']),
            'action_date' => $this->faker->date(),
            'updated_by'  => User::factory(),
        ];
    }
}
