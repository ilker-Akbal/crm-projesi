<?php
namespace Database\Factories;

use App\Models\SupportRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportRequestFactory extends Factory
{
    protected $model = SupportRequest::class;

    public function definition(): array
    {
        return [
            'customer_id'      => Customer::factory(),
            'title'            => $this->faker->sentence(5),
            'explanation'      => $this->faker->paragraph,
            'situation'        => $this->faker->randomElement(['açık','çözüldü','beklemede']),
            'registration_date'=> $this->faker->date(),
            'updated_by'       => User::factory(),
        ];
    }
}
