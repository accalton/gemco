<?php

namespace Database\Seeders;

use App\Models\Member;
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
        Membership::factory(1)->hasAttached(
            Member::factory(2),
            ['type' => 'member']
        )->hasAttached(
            User::factory(1),
            ['type' => 'contact']
        )->create();
    }
}
