<?php

namespace Database\Seeders;

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
            'name' => 'Adrian Calton',
            'email' => 'accalton@gmail.com',
            'password' => 'password'
        ]);

        $this->seedMemberships();
    }

    /**
     * @return void
     */
    private function seedMemberships(): void
    {
        $memberships = Membership::factory(100)->create();

        foreach ($memberships as $membership) {
            $factory = User::factory()
                ->hasAttached($membership, [
                    'type' => MembershipUser::TYPE_MEMBER
                ]);

            if ($membership->type === Membership::TYPE_FAMILY) {
                $factory = $factory->count(rand(2, 5));
            }

            $factory->create();
        }
    }
}
