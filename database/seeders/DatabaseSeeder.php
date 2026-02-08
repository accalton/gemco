<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Member;
use App\Models\MemberMembership;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'Adrian Calton',
            'email'    => 'accalton@gmail.com',
            'password' => 'password'
        ]);

        User::factory()->create([
            'name'     => 'Jessica Fraser',
            'email'    => 'gemcoplayers@gmail.com',
            'password' => 'password'
        ]);

        $this->seedMemberships();
    }

    /**
     * @return void
     */
    private function seedMemberships(): void
    {
        $addresses = Address::factory()->count(10)->create();

        foreach ($addresses as $address) {
            $membershipType = array_rand(Membership::TYPES);

            $numberOfMembers = 1;
            if ($membershipType === Membership::TYPE_FAMILY) {
                $numberOfMembers = rand(2, 5);
            }

            Membership::factory()->state([
                'type' => $membershipType
            ])->hasAttached(
                Member::factory()->count($numberOfMembers)->for($address),
                ['type' => MemberMembership::TYPE_MEMBER]
            )->hasAttached(
                Member::factory()->count(rand(1, 3)),
                ['type' => MemberMembership::TYPE_CONTACT]
            )->create();
        }

        Member::factory(10)->for(Address::factory())->create();
    }
}
