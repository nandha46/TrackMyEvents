<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use DateTime;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::factory()->create([
            'id' => 1,
            'name' => 'Nandha',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'me.jpg',
        ]);
        
        User::factory(10)->create();

        Event::create([
            'user_id' => 1,
            'event_name' => 'Acer Aspire 7',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => false,
            'background' => 'gradient',
            'fields' => 'days, months'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'S21 FE',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => false,
            'background' => 'gradient2',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'Oneplus 6T',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '7.jpg',
            'fields' => 'months'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'Gym DayOne',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '6.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'First International Trip',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '5.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'New Bike',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '4.jpg',
            'fields' => 'years'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'First Job',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '3.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'Bought New Car',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '2.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'Quit Drinking',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => '1.jpg',
            'fields' => 'days'
        ]);
    }
}
