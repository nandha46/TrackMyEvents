<?php

namespace Tests\Unit;

use App\Models\Event;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class EventCalculationTest extends TestCase
{
    public function test_past_event_detection(): void
    {
        $event = new Event([
            'event_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
        ]);

        $this->assertTrue($event->isPastEvent());
    }

    public function test_future_event_detection(): void
    {
        $event = new Event([
            'event_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
        ]);

        $this->assertFalse($event->isPastEvent());
    }

    public function test_date_difference_helpers(): void
    {
        $event = new Event([
            'event_date' => Carbon::now()->addYear()->addMonths(2)->addDays(5)->format('Y-m-d'),
        ]);

        $this->assertGreaterThanOrEqual(1, $event->getYears());
        $this->assertGreaterThanOrEqual(0, $event->getMonths());
        $this->assertGreaterThanOrEqual(0, $event->getDays());
        $this->assertGreaterThanOrEqual(0, $event->getWeeks());
    }
}
