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
        $issued->modify('-' . rand(0, 550) . ' days');

        $expiry = clone $issued;
        $expiry->modify('+1 year');

        return [
            'type' => array_rand(Identification::TYPES),
            'number' => fake()->randomNumber(8, true),
            'expiry' => $expiry->format('Y-m-d'),
        ];
    }
}
