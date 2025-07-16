<?php
namespace Database\Factories;

use App\Models\Offer;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        return [
            'customer_id'  => Customer::factory(),
            'order_id'     => null,                    // isteğe bağlı: Order::factory()
            'offer_date'   => $this->faker->dateTimeBetween('-1 month','now'),
            'valid_until'  => $this->faker->dateTimeBetween('now','+1 month'),
            'status'       => $this->faker->randomElement(['hazırlanıyor','gönderildi','kabul','reddedildi']),
            'total_amount' => 0,                       // satırlarda güncellenir
            'created_by'   => User::factory(),
            'updated_by'   => User::factory(),
        ];
    }
}
