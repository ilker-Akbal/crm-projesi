<?php
namespace Database\Factories;

use App\Models\ProductStock;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductStockFactory extends Factory
{
    protected $model = ProductStock::class;

    public function definition(): array
    {
        return [
            'product_id'      => Product::factory(),
            'stock_quantity'  => $this->faker->numberBetween(0, 500),
            'update_date'     => $this->faker->date(),
            'updated_by'      => User::factory(),
        ];
    }
}
