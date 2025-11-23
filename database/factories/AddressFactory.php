<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'by_user_id'   => User::factory(),
            'line1'        => $this->faker->streetAddress(),
            'line2'        => $this->faker->optional()->secondaryAddress(),
            'city'         => $this->faker->city(),
            'province'     => $this->faker->randomElement([
                'Western Cape',
                'Gauteng',
                'KwaZulu-Natal',
                'Eastern Cape',
                'Free State',
                'Limpopo',
                'Mpumalanga',
                'Northern Cape',
                'North West'
            ]),
            'postal'       => $this->faker->postcode(),
            'country_code' => 'ZA',
            'status'       => Address::STATUS_PENDING,
            'last_error'   => null,
        ];
    }
}