<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_events_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_event(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/events', [
            'event_name' => 'Project Launch',
            'event_date' => '2026-12-01',
            'event_time' => '10:00',
            'event_type' => 'Work',
            'fields' => ['days', 'months', 'years'],
            'is_background_image' => '0',
            'background_gradient' => 'from-purple-500 to-indigo-600',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('events', [
            'event_name' => 'Project Launch',
            'event_type' => 'Work',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_single_event(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'event_name' => 'Birthday Party',
            'event_date' => '2026-10-15',
            'event_type' => 'Personal',
            'fields' => 'days,months,years',
            'is_background_image' => false,
            'background' => 'from-purple-500 to-indigo-600',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/events/' . $event->id);

        $response->assertStatus(200);
        $response->assertSee('Birthday Party');
    }

    public function test_user_can_update_event(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'event_name' => 'Old Title',
            'event_date' => '2026-10-15',
            'event_type' => 'Personal',
            'fields' => 'days,months,years',
            'is_background_image' => false,
            'background' => 'from-purple-500 to-indigo-600',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put('/events/' . $event->id, [
            'event_name' => 'Updated Title',
            'event_date' => '2026-10-15',
            'event_type' => 'Work',
            'fields' => ['days', 'months'],
        ]);

        $response->assertRedirect('/events/' . $event->id);
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'event_name' => 'Updated Title',
            'event_type' => 'Work',
        ]);
    }

    public function test_user_can_delete_event(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'event_name' => 'Delete Me',
            'event_date' => '2026-10-15',
            'event_type' => 'Personal',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'from-purple-500 to-indigo-600',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete('/events/' . $event->id);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    public function test_user_can_export_events_csv(): void
    {
        $user = User::factory()->create();
        Event::create([
            'event_name' => 'Export Event',
            'event_date' => '2026-10-15',
            'event_type' => 'Personal',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'from-purple-500 to-indigo-600',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/profile/export');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
