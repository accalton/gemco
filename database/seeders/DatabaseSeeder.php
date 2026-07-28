<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Group;
use App\Models\Identification;
use App\Models\Membership;
use App\Models\MembershipUser;
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

        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@gemcoplayers.org',
            'password' => 'password',
        ]);

        $groups = [
            'Lifetime',
            'Youth',
        ];

        foreach ($groups as $group) {
            Group::factory()->create([
                'title' => $group
            ]);
        }
        
        $this->seedMemberships();
    }

    public function seedMemberships(): void
    {
        $users = User::factory()
            ->has(Address::factory())
            ->has(Identification::factory())
            ->count(50)
            ->create();

        foreach ($users as $user) {
            $type = array_rand(Membership::TYPES);

            $membership = Membership::factory()
                ->state([
                    'type' => $type
                ])
                ->hasAttached($user, ['type' => MembershipUser::TYPE_MEMBER], 'members')
                ->create();

            if ($type === Membership::TYPE_FAMILY) {
                User::factory()
                    ->hasAttached($membership, ['type' => MembershipUser::TYPE_MEMBER], 'memberships')
                    ->count(rand(1, 3))
                    ->create();
            }
        }
    }
}
