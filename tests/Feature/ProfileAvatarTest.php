<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->patch('/profile-avatar', [
            'avatar' => $file,
        ]);

        $response->assertRedirect('/profile');
        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertNotEquals('default.png', $user->avatar);
    }

    public function test_user_can_export_events_json(): void
    {
        $user = User::factory()->create();
        Event::create([
            'event_name' => 'JSON Event',
            'event_date' => '2026-10-15',
            'event_type' => 'Personal',
            'fields' => 'days',
            'is_background_image' => false,
            'background' => 'gradient-1',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/profile/export?format=json');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $this->assertStringContainsString('JSON Event', $response->getContent());
    }

    public function test_user_can_download_csv_and_json_templates(): void
    {
        $user = User::factory()->create();

        $resCsv = $this->actingAs($user)->get('/profile/template/csv');
        $resCsv->assertStatus(200);
        $resCsv->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $resJson = $this->actingAs($user)->get('/profile/template/json');
        $resJson->assertStatus(200);
        $resJson->assertHeader('content-type', 'application/json');
    }

    public function test_user_can_import_events_json(): void
    {
        $user = User::factory()->create();

        $jsonData = json_encode([
            [
                'event_name' => 'Imported Birthday',
                'event_date' => '2026-12-25',
                'event_time' => '12:00:00',
                'event_type' => 'Reminder',
                'fields' => 'days,months,years',
                'is_recurring' => true,
                'recurrence_type' => 'yearly',
                'tags' => ['family'],
                'description' => 'Celebrate Christmas birthday',
            ]
        ]);

        $file = UploadedFile::fake()->createWithContent('events.json', $jsonData);

        $response = $this->actingAs($user)->post('/profile/import', [
            'import_file' => $file,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('events', [
            'event_name' => 'Imported Birthday',
            'user_id' => $user->id,
            'recurrence_type' => 'yearly',
        ]);
    }
}
