<?php
namespace Database\Factories;

use App\Models\CurrentMovement;
use App\Models\CurrentCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrentMovementFactory extends Factory
{
    protected $model = CurrentMovement::class;

    public function definition(): array
    {
        return [
            'current_id'     => CurrentCard::factory(),
            'departure_date' => $this->faker->date(),
            'amount'         => $this->faker->randomFloat(2, -1000, 1000),
            'movement_type'  => $this->faker->randomElement(['giriş','çıkış']),
            'explanation'    => $this->faker->sentence,
            'updated_by'     => User::factory(),
        ];
    }
}
