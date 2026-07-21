<?php

namespace Database\Seeders;

use App\Models\Group;
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

        $groups = [
            'Lifetime',
            'Youth',
        ];

        foreach ($groups as $group) {
            Group::factory()->create($group);
        }
    }
}
