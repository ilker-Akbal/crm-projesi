<?php
namespace Database\Factories;

use App\Models\ProductPrice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'price'      => $this->faker->randomFloat(2, 10, 500),
            'updated_by' => User::factory(),
        ];
    }
}
