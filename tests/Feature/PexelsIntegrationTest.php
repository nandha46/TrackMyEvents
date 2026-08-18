<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use App\Services\PexelsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PexelsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pexels_search_endpoint_returns_curated_when_no_query(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('pexels.search'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'is_curated',
            'photos',
            'rate_limits' => [
                'limit',
                'remaining',
                'reset',
            ]
        ]);
        $this->assertTrue($response->json('is_curated'));
    }

    public function test_pexels_search_endpoint_with_query(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('pexels.search', ['query' => 'vacation']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'photos',
        ]);
    }

    public function test_pexels_rate_limits_page_is_accessible(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.pexels_stats'));

        $response->assertStatus(200);
        $response->assertSee('Pexels API Rate Limit Statistics');
        $response->assertSee('X-Ratelimit-Limit');
        $response->assertSee('X-Ratelimit-Remaining');
    }

    public function test_create_event_with_pexels_photo_and_attribution(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('event.store'), [
            'event_name' => 'Trip to Hawaii',
            'event_date' => '2027-06-15',
            'event_time' => '10:00',
            'event_type' => 'Personal',
            'is_background_image' => '1',
            'pexels_id' => '1024960',
            'pexels_photographer' => 'Asad Photo Maldives',
            'pexels_photographer_url' => 'https://www.pexels.com/@asadphotography',
            'pexels_photo_url' => 'https://www.pexels.com/photo/island-1024960/',
            'pexels_download_url' => 'https://images.pexels.com/photos/1024960/pexels-photo-1024960.jpeg',
            'recurrence_type' => 'none',
        ]);

        $response->assertRedirect(route('dashboard'));

        $event = Event::where('event_name', 'Trip to Hawaii')->first();
        $this->assertNotNull($event);
        $this->assertEquals('1024960', $event->pexels_id);
        $this->assertEquals('Asad Photo Maldives', $event->pexels_photographer);
        $this->assertTrue($event->is_background_image);

        // Check show view contains attribution
        $showResponse = $this->actingAs($user)->get(route('event.show', $event->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Asad Photo Maldives');
        $showResponse->assertSee('Pexels');
    }
}
