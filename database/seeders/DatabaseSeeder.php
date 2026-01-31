<?php

namespace Database\Seeders;

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
        Membership::factory()->hasAttached(
            Member::factory(3),
            ['type' => MemberMembership::TYPE_MEMBER]
        )->hasAttached(
            Member::factory(1),
            ['type' => MemberMembership::TYPE_CONTACT]
        )->create();

        Member::factory(10)->create();
    }
}
