<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'company_name'     => $this->faker->company,
            'tax_number'       => $this->faker->unique()->numerify('##########'),
            'address'          => $this->faker->address,
            'phone_number'     => $this->faker->phoneNumber,
            'email'            => $this->faker->companyEmail,
            'registration_date'=> $this->faker->date(),
            'current_role'     => $this->faker->randomElement(['candidate','customer','supplier']),
            'customer_id'      => Customer::factory(),
            'created_by'       => User::factory(),
            'updated_by'       => User::factory(),
        ];
    }
}
