<?php
namespace Database\Factories;

use App\Models\OfferProduct;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferProductFactory extends Factory
{
    protected $model = OfferProduct::class;

    public function definition(): array
    {
        return [
            'offer_id'   => Offer::factory(),
            'product_id' => Product::factory(),
            'amount'     => $this->faker->numberBetween(1, 10),
            'unit_price' => $this->faker->randomFloat(2, 10, 200),
            'updated_by' => User::factory(),
        ];
    }
}
