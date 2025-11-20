<?php

namespace Database\Factories;

use App\Models\Identification;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Identification>
 */
class IdentificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $issued = new DateTime();
        $issued->modify('-' . rand(100, 500) . ' days');

        $expiry = clone $issued;
        $expiry->modify('+2 years');

        return [
            'type' => array_rand(Identification::TYPES),
            'number' => fake()->randomNumber(8, true),
            'issued' => $issued->format('Y-m-d'),
            'expiry' => $expiry->format('Y-m-d'),
        ];
    }
}
