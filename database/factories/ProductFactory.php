<?php
namespace Database\Factories;

use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_name' => $this->faker->word.' '.$this->faker->colorName,
            'customer_id'  => Customer::factory(),   // opsiyonel
            'explanation'  => $this->faker->sentence,
            'created_by'   => User::factory(),
            'updated_by'   => User::factory(),
        ];
    }
}
