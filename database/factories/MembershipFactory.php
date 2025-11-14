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
        $types = array_keys(Membership::TYPES);
        $type = $types[rand(0, count($types) - 1)];
        $statuses = array_keys(Membership::STATUSES);
        $status = $statuses[rand(0, count($statuses) - 1)];

        if (in_array($status, [Membership::STATUS_CANCELLED, Membership::STATUS_EXPIRED])) {
            $date->modify('-' . rand(1, 52) . ' weeks');
        } else {
            $date->modify('+' . rand(1, 52) . ' weeks');
        }

        return [
            'expiry' => $date->format('Y-m-d'),
            'status' => $status,
            'type' => $type,
        ];
    }
}
