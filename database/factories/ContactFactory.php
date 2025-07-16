<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name'       => $this->faker->name,
            'position'   => $this->faker->jobTitle,
            'email'      => $this->faker->safeEmail,
            'phone'      => $this->faker->phoneNumber,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
