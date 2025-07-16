<?php
namespace Database\Factories;

use App\Models\Reminder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition(): array
    {
        return [
            'title'         => $this->faker->sentence(4),
            'reminder_date' => $this->faker->dateTimeBetween('now','+1 month'),
            'customer_id'   => Customer::factory(),
            'user_id'       => User::factory(),
            'explanation'   => $this->faker->paragraph,
            'updated_by'    => User::factory(),
        ];
    }
}
