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

        User::factory()->create([
            'name'     => 'Matthew Greenaway',
            'email'    => 'gemcopresident@gmail.com',
            'password' => 'password'
        ]);

        $this->seedMemberships();
    }

    /**
     * @return void
     */
    private function seedMemberships(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $membershipType = array_rand(Membership::TYPES);

            $membership = Membership::factory()
                ->state([
                    'type' => $membershipType
                ])
                ->for(Address::factory())
                ->for(Member::factory(), 'member')
                ->hasAttached(
                    Member::factory()->count(rand(1, 2)),
                    ['type' => MemberMembership::TYPE_CONTACT]
                );

            if ($membershipType === Membership::TYPE_FAMILY) {
                $membership = $membership->hasAttached(
                    Member::factory()->count(rand(1, 3)),
                    ['type' => MemberMembership::TYPE_MEMBER]
                );
            }

            $membership->create();
        }
    }

    /**
     * @return void
     */
    private function seedMemberships_old(): void
    {
        $addresses = Address::factory()->count(10)->create();

        foreach ($addresses as $address) {
            $membershipType = array_rand(Membership::TYPES);

            $membership = Membership::factory()
                ->for(Member::factory(), 'member')
                ->for($address)
                ->state([
                    'type' => $membershipType
                ])/*->hasAttached(
                    Member::factory()->count(rand(1, 2)),
                    ['type' => MemberMembership::TYPE_CONTACT]
                )*/;

            if ($membershipType === Membership::TYPE_FAMILY) {
                // $membership->hasAttached(
                //     Member::factory()->count(rand(1, 3)),
                //     ['type' => MemberMembership::TYPE_MEMBER]
                // );
            }

            $membership->create();
        }

        Member::factory(10)->for(Address::factory())->create();
    }
}
