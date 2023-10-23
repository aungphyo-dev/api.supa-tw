<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Following;
use App\Models\Tweet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory(20)->create();
        Tweet::factory(100)->create();
        Following::factory(20)->create();
//
//         \App\Models\User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);
    }
}
