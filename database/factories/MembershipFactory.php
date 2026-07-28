<?php

namespace Database\Factories;

use App\Models\Membership;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = new DateTime();
        $status = array_rand(Membership::STATUSES);

        return [
            'type'   => array_rand(Membership::TYPES),
            'status' => $status,
            'expiry' => rand(0, 3) ?
                $date->modify('+' . rand(1, 365) . ' days')->format('Y-m-d') :
                $date->modify('-' . rand(1, 365) . ' days')->format('Y-m-d'),
            'cancellation_reason' => ''
        ];
    }
}
