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
        
        User::factory()->create([
            'id' => 1,
            'name' => 'Nandha',
            'email' => 'nandha@mail.com',
            'password' => '$2y$12$cixxaYdnFGxB7W3qqfDOleeo1lYTHZLTrrE0pFCFNgQxXrOw4x0eO'
        ]);
        
        User::factory(10)->create();

        Event::create([
            'user_id' => 1,
            'event_name' => 'Acer Aspire 7',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => false,
            'background' => 'gradient',
            'fields' => 'days'
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
            'background' => 'uploads/backgrounds/patrick-langwallner-nLhAXKQAp6A-unsplash.jpg',
            'fields' => 'months'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'Chennai DayOne',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => 'uploads/backgrounds/carlos-MrfOUghIAJ0-unsplash.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'First AC',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => 'uploads/backgrounds/kevin-mueller-NzoJDM2CmH0-unsplash.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'New Bike',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => 'uploads/backgrounds/patrick-langwallner-nLhAXKQAp6A-unsplash.jpg',
            'fields' => 'years'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'First Job',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => 'uploads/backgrounds/carlos-MrfOUghIAJ0-unsplash.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'New Car : Renault',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => 'uploads/backgrounds/annie-spratt-aauZUpeKOTE-unsplash.jpg',
            'fields' => 'days'
        ]);
        Event::create([
            'user_id' => 1,
            'event_name' => 'Quit Drinking',
            'event_date' => new DateTime('24-06-2020'),
            'event_type' => 'Personal',
            'is_background_image' => true,
            'background' => 'uploads/backgrounds/patrick-langwallner-nLhAXKQAp6A-unsplash.jpg',
            'fields' => 'days'
        ]);
    }
}
