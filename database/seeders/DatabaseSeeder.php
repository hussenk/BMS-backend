<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'role' => UserRoleEnum::Admin
        ]);
        User::factory()->create([
            'name' => 'Hussen',
            'email' => 'hussen@example.com',
            'role' => UserRoleEnum::Admin
        ]);

        User::factory(10)->create();

        $this->call([
            AuthorSeeder::class,
        ]);
    }
}
