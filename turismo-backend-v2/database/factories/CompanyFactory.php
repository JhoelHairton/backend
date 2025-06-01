<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
                      'business_name' => $this->faker->company(),
            'trade_name' => $this->faker->companySuffix(),
            'service_type' => $this->faker->word(),
            'contact_email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'website' => $this->faker->optional()->url(),
            'description' => $this->faker->optional()->paragraph(),
            'ruc' => $this->faker->unique()->numerify('20#########'), // RUC de 11 dígitos
            'logo_url' => null,
            'status' => 'pendiente',
            'verified_at' => null,
            'user_id' => User::factory(),

        ];
    }
}
