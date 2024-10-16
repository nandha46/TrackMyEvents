<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use DateTime;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Nandha'.(string)rand(0,999),
        //     'email' => 'nandha'.(string)rand(0,999).'@example.com',
        // ]);

        Event::create([
            'user_id' => 1,
            'event_name' => 'Acer Aspire 7',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
        ]);
    }
}
