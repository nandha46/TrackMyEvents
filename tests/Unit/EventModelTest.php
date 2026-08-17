<?php

namespace Tests\Unit;

use App\Models\Event;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class EventModelTest extends TestCase
{
    public function test_milestone_progress_calculation(): void
    {
        $event = new Event([
            'milestones' => [
                ['id' => '1', 'title' => 'Task 1', 'completed' => true],
                ['id' => '2', 'title' => 'Task 2', 'completed' => false],
                ['id' => '3', 'title' => 'Task 3', 'completed' => true],
                ['id' => '4', 'title' => 'Task 4', 'completed' => false],
            ]
        ]);

        $this->assertEquals(50, $event->getMilestonesProgress());
    }

    public function test_gradient_classes_mapping(): void
    {
        $event1 = new Event(['background' => 'gradient-1']);
        $this->assertEquals('from-purple-500 to-indigo-600', $event1->getGradientClasses());

        $event2 = new Event(['background' => 'gradient-2']);
        $this->assertEquals('from-blue-500 to-teal-500', $event2->getGradientClasses());
    }

    public function test_ical_string_generation(): void
    {
        $event = new Event([
            'event_name' => 'Tech Summit 2026',
            'event_date' => '2026-11-15',
            'event_time' => '09:00:00',
            'event_type' => 'Work',
            'description' => 'Keynote presentations',
        ]);

        $ics = $event->toIcs();
        $this->assertStringContainsString('BEGIN:VCALENDAR', $ics);
        $this->assertStringContainsString('SUMMARY:Tech Summit 2026', $ics);
        $this->assertStringContainsString('END:VCALENDAR', $ics);
    }

    public function test_google_calendar_url_generation(): void
    {
        $event = new Event([
            'event_name' => 'Beach Vacation',
            'event_date' => '2026-08-20',
            'event_time' => '10:00:00',
            'event_type' => 'Personal',
        ]);

        $url = $event->google_calendar_url;
        $this->assertStringContainsString('calendar.google.com/calendar/render', $url);
        $this->assertStringContainsString('Beach+Vacation', $url);
    }
}
