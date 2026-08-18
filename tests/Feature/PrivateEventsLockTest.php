<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PrivateEventsLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_set_and_verify_private_pin(): void
    {
        $user = User::factory()->create();

        // 1. Initial setup of PIN via verify endpoint
        $response = $this->actingAs($user)->postJson(route('private.verify_pin'), [
            'pin' => '1234',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $user->refresh();
        $this->assertTrue($user->hasPrivatePin());
        $this->assertTrue($user->verifyPrivatePin('1234'));
        $this->assertFalse($user->verifyPrivatePin('9999'));

        // 2. Subsequent verification with correct and incorrect PIN
        $correct = $this->actingAs($user)->postJson(route('private.verify_pin'), [
            'pin' => '1234',
        ]);
        $correct->assertStatus(200);

        $incorrect = $this->actingAs($user)->postJson(route('private.verify_pin'), [
            'pin' => '0000',
        ]);
        $incorrect->assertStatus(422);
    }

    public function test_user_can_update_pin_from_profile(): void
    {
        $user = User::factory()->create([
            'private_pin' => Hash::make('1234'),
        ]);

        $response = $this->actingAs($user)->post(route('private.set_pin'), [
            'current_pin' => '1234',
            'pin' => '5678',
            'pin_confirmation' => '5678',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue($user->verifyPrivatePin('5678'));
    }

    public function test_events_filtering_by_tag(): void
    {
        $user = User::factory()->create();

        Event::create([
            'event_name' => 'Trip to Japan',
            'event_date' => '2027-08-10',
            'event_type' => 'Personal',
            'fields' => 'days,months,years',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'tags' => ['travel', 'vacation'],
            'user_id' => $user->id,
        ]);

        Event::create([
            'event_name' => 'Quarterly Meeting',
            'event_date' => '2027-09-01',
            'event_type' => 'Work',
            'fields' => 'days,months,years',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'tags' => ['work', 'strategy'],
            'user_id' => $user->id,
        ]);

        // Filter by travel tag
        $response = $this->actingAs($user)->get(route('dashboard', ['tag' => 'travel']));
        $response->assertStatus(200);
        $response->assertSee('Trip to Japan');
        $response->assertDontSee('Quarterly Meeting');

        // Filter by work tag
        $responseWork = $this->actingAs($user)->get(route('dashboard', ['tag' => 'work']));
        $responseWork->assertStatus(200);
        $responseWork->assertSee('Quarterly Meeting');
        $responseWork->assertDontSee('Trip to Japan');
    }
}
