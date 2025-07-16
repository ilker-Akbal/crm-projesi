<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'situation'     => $this->faker->randomElement(['hazırlanıyor','tamamlandı']),
            'customer_id'   => Customer::factory(),
            'order_date'    => $this->faker->dateTimeBetween('-2 months','now'),
            'delivery_date' => $this->faker->dateTimeBetween('now','+1 month'),
            'total_amount'  => 0,                        // satırlarda güncellenir
            'created_by'    => User::factory(),
            'updated_by'    => User::factory(),
        ];
    }
}
