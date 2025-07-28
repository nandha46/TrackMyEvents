<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            'name' => 'Test Example',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory(99)->create();
        
        $this->call(EventSeeder::class);

    }
}
