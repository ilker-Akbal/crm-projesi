<?php
namespace Database\Factories;

use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderProductFactory extends Factory
{
    protected $model = OrderProduct::class;

    public function definition(): array
    {
        return [
            'order_id'    => Order::factory(),
            'product_id'  => Product::factory(),
            'amount'      => $this->faker->numberBetween(1, 10),
            'unit_price'  => $this->faker->randomFloat(2, 10, 200),
            'updated_by'  => User::factory(),
        ];
    }
}
