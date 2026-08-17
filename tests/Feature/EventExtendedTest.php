<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventExtendedTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_event(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $event = Event::create([
            'event_name' => 'Secret Event',
            'event_date' => '2026-12-31',
            'event_type' => 'Private',
            'fields' => 'days,months,years',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->get('/events/' . $event->id);
        $response->assertStatus(404);
    }

    public function test_user_cannot_edit_or_delete_another_users_event(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $event = Event::create([
            'event_name' => 'User1 Event',
            'event_date' => '2026-12-31',
            'event_type' => 'Personal',
            'fields' => 'days,months,years',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user1->id,
        ]);

        // Try update
        $updateResponse = $this->actingAs($user2)->put('/events/' . $event->id, [
            'event_name' => 'Hacked Title',
            'event_date' => '2026-12-31',
            'event_type' => 'Personal',
        ]);
        $updateResponse->assertStatus(404);

        // Try delete
        $deleteResponse = $this->actingAs($user2)->delete('/events/' . $event->id);
        $deleteResponse->assertStatus(404);

        $this->assertDatabaseHas('events', ['id' => $event->id, 'event_name' => 'User1 Event']);
    }

    public function test_search_and_filtering_on_dashboard(): void
    {
        $user = User::factory()->create();

        Event::create([
            'event_name' => 'Hawaii Summer Vacation',
            'event_date' => '2026-07-20',
            'event_type' => 'Personal',
            'fields' => 'days,months',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user->id,
        ]);

        Event::create([
            'event_name' => 'Q4 Board Meeting',
            'event_date' => '2026-11-15',
            'event_type' => 'Work',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'gradient-2',
            'user_id' => $user->id,
        ]);

        // Filter by Work
        $resWork = $this->actingAs($user)->get('/dashboard?filter=Work');
        $resWork->assertStatus(200);
        $resWork->assertSee('Q4 Board Meeting');
        $resWork->assertDontSee('Hawaii Summer Vacation');

        // Search
        $resSearch = $this->actingAs($user)->get('/dashboard?search=Hawaii');
        $resSearch->assertStatus(200);
        $resSearch->assertSee('Hawaii Summer Vacation');
        $resSearch->assertDontSee('Q4 Board Meeting');

        // Views: Timeline and Calendar
        $resTimeline = $this->actingAs($user)->get('/dashboard?view=timeline');
        $resTimeline->assertStatus(200);

        $resCalendar = $this->actingAs($user)->get('/dashboard?view=calendar');
        $resCalendar->assertStatus(200);
    }

    public function test_public_share_link_accessible_to_guest(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'event_name' => 'Community Hackathon',
            'event_date' => '2026-09-01',
            'event_type' => 'Work',
            'fields' => 'days,hours',
            'is_background_image' => false,
            'background' => 'gradient-3',
            'share_token' => 'test-public-token-12345',
            'user_id' => $user->id,
        ]);

        $response = $this->get('/share/' . $event->share_token);
        $response->assertStatus(200);
        $response->assertSee('Community Hackathon');
    }

    public function test_ical_export_download(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'event_name' => 'Tokyo Conference',
            'event_date' => '2026-10-10',
            'event_type' => 'Work',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/events/' . $event->id . '/ical');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/calendar; charset=utf-8');
        $this->assertStringContainsString('Tokyo Conference', $response->getContent());
    }

    public function test_bulk_delete_events(): void
    {
        $user = User::factory()->create();
        $e1 = Event::create([
            'event_name' => 'Event 1',
            'event_date' => '2026-10-10',
            'event_type' => 'Personal',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user->id,
        ]);
        $e2 = Event::create([
            'event_name' => 'Event 2',
            'event_date' => '2026-10-11',
            'event_type' => 'Personal',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post('/events/bulk-delete', [
            'ids' => json_encode([$e1->id, $e2->id]),
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('events', ['id' => $e1->id]);
        $this->assertDatabaseMissing('events', ['id' => $e2->id]);
    }

    public function test_milestone_checklist_toggle(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'event_name' => 'Trip with Milestones',
            'event_date' => '2026-11-20',
            'event_type' => 'Personal',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'milestones' => [
                ['id' => '1', 'title' => 'Book Hotel', 'completed' => false],
                ['id' => '2', 'title' => 'Rent Car', 'completed' => false],
            ],
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->postJson('/events/' . $event->id . '/milestones/toggle', [
            'milestone_id' => '1',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'progress' => 50,
        ]);

        $event->refresh();
        $this->assertTrue($event->milestones[0]['completed']);
    }
}
