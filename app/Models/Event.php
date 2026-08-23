<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'event_date',
        'event_time',
        'event_type',
        'fields',
        'is_background_image',
        'background',
        'pexels_id',
        'pexels_photographer',
        'pexels_photographer_url',
        'pexels_photo_url',
        'user_id',
        'share_token',
        'is_recurring',
        'recurrence_type',
        'tags',
        'milestones',
        'description',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'is_background_image' => 'boolean',
        'tags' => 'array',
        'milestones' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->share_token)) {
                $event->share_token = Str::random(32);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the selected fields as an array (e.g. ['years', 'months', 'days', 'hours', 'minutes', 'seconds']).
     */
    public function getSelectedFieldsArray(): array
    {
        if (empty($this->fields)) {
            return ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
        }

        $fields = array_map('strtolower', array_map('trim', explode(',', $this->fields)));
        return !empty($fields) ? $fields : ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
    }

    /**
     * Check if a specific countdown field is selected for this event.
     */
    public function hasField(string $field): bool
    {
        return in_array(strtolower(trim($field)), $this->getSelectedFieldsArray(), true);
    }


    /**
     * Get the effective target Carbon datetime instance considering recurrence.
     */
    public function getTargetDateTime(): Carbon
    {
        $timeStr = $this->event_time ? $this->event_time : '00:00:00';
        $baseDate = Carbon::parse($this->getRawOriginal('event_date') ?? $this->event_date);
        $target = Carbon::parse($baseDate->format('Y-m-d') . ' ' . $timeStr);

        if (!$this->is_recurring || $this->recurrence_type === 'none') {
            return $target;
        }

        $now = now();

        switch ($this->recurrence_type) {
            case 'yearly':
                $next = $target->copy()->year($now->year);
                if ($next->isPast() && !$next->isSameDay($now)) {
                    $next->addYear();
                }
                return $next;

            case 'monthly':
                $next = $target->copy()->year($now->year)->month($now->month);
                if ($next->isPast() && !$next->isSameDay($now)) {
                    $next->addMonth();
                }
                return $next;

            case 'weekly':
                $next = $now->copy()->setTimeFromTimeString($timeStr);
                $dayDiff = ($baseDate->dayOfWeek - $now->dayOfWeek + 7) % 7;
                $next->addDays($dayDiff);
                if ($next->isPast() && !$next->isSameDay($now)) {
                    $next->addWeek();
                }
                return $next;

            case 'daily':
                $next = $now->copy()->setTimeFromTimeString($timeStr);
                if ($next->isPast()) {
                    $next->addDay();
                }
                return $next;

            default:
                return $target;
        }
    }

    public function isPastEvent(): bool
    {
        return $this->getTargetDateTime()->isPast();
    }

    public function countDown(): string
    {
        $target = $this->getTargetDateTime();
        return $target->diffInYears(now()) . "Y " . ($target->diffInMonths(now()) % 12) . "M";
    }

    public function getYears(): int
    {
        return (int) floor(abs($this->getTargetDateTime()->diffInYears(now())));
    }

    public function getMonths(): int
    {
        return (int) (floor(abs($this->getTargetDateTime()->diffInMonths(now()))) % 12);
    }

    public function getWeeks(): int
    {
        return (int) floor(abs($this->getTargetDateTime()->diffInWeeks(now())));
    }

    public function getDays(): int
    {
        return (int) (floor(abs($this->getTargetDateTime()->diffInDays(now()))) % 30);
    }

    public function getTotalDays(): int
    {
        return (int) floor(abs($this->getTargetDateTime()->diffInDays(now())));
    }

    public function getHours(): int
    {
        return (int) (floor(abs($this->getTargetDateTime()->diffInHours(now()))) % 24);
    }

    public function getMinutes(): int
    {
        return (int) (floor(abs($this->getTargetDateTime()->diffInMinutes(now()))) % 60);
    }

    public function getSeconds(): int
    {
        return (int) (floor(abs($this->getTargetDateTime()->diffInSeconds(now()))) % 60);
    }

    public function getWorkdays(): int
    {
        $from = $this->getTargetDateTime();
        $to = now();
        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }
        return (int) floor(abs($from->diffInWeekdays($to)));
    }

    /**
     * Get milestone progress percentage (0 - 100).
     */
    public function getMilestonesProgress(): int
    {
        $milestones = is_array($this->milestones) ? $this->milestones : [];
        if (empty($milestones)) {
            return 0;
        }

        $completed = count(array_filter($milestones, fn($m) => !empty($m['completed'])));
        return (int) round(($completed / count($milestones)) * 100);
    }

    /**
     * Generate Google Calendar Add URL.
     */
    public function getGoogleCalendarUrlAttribute(): string
    {
        $start = $this->getTargetDateTime();
        $end = $start->copy()->addHour();

        $dates = $start->format('Ymd\THis\Z') . '/' . $end->format('Ymd\THis\Z');
        $title = urlencode($this->event_name);
        $details = urlencode($this->description ?? "Tracked via TrackMyEvents - {$this->event_type}");

        return "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$dates}&details={$details}";
    }

    /**
     * Generate RFC 5545 iCalendar content string.
     */
    public function toIcs(): string
    {
        $start = $this->getTargetDateTime();
        $end = $start->copy()->addHour();
        $uid = md5($this->id . $this->created_at) . '@trackmyevents.com';
        $summary = preg_replace('/[^\w\s\-.,!]/', '', $this->event_name);
        $description = preg_replace('/[^\w\s\-.,!]/', '', $this->description ?? "Event: {$this->event_name} ({$this->event_type})");

        return "BEGIN:VCALENDAR\r\n" .
            "VERSION:2.0\r\n" .
            "PRODID:-//TrackMyEvents//NONSGML Event Tracker//EN\r\n" .
            "CALSCALE:GREGORIAN\r\n" .
            "METHOD:PUBLISH\r\n" .
            "BEGIN:VEVENT\r\n" .
            "UID:{$uid}\r\n" .
            "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n" .
            "DTSTART:" . $start->format('Ymd\THis\Z') . "\r\n" .
            "DTEND:" . $end->format('Ymd\THis\Z') . "\r\n" .
            "SUMMARY:{$summary}\r\n" .
            "DESCRIPTION:{$description}\r\n" .
            "STATUS:CONFIRMED\r\n" .
            "END:VEVENT\r\n" .
            "END:VCALENDAR\r\n";
    }

    public function getGradientClasses(): string
    {
        $bg = trim($this->background ?? '');

        $map = [
            'gradient-1' => 'from-purple-500 to-indigo-600',
            'gradient-2' => 'from-blue-500 to-teal-500',
            'gradient-3' => 'from-pink-500 to-rose-500',
            'gradient-4' => 'from-amber-500 to-orange-600',
            'gradient-5' => 'from-emerald-500 to-green-600',
            'gradient-6' => 'from-cyan-500 to-blue-600',
            'gradient-7' => 'from-violet-600 to-fuchsia-600',
            'gradient-8' => 'from-rose-500 to-purple-600',
            'gradient-9' => 'from-teal-400 to-indigo-700',
            'gradient' => 'from-purple-600 to-indigo-700',
            'gradient2' => 'from-pink-500 to-purple-600',
        ];

        if (isset($map[$bg])) {
            return $map[$bg];
        }

        if (str_starts_with($bg, 'from-')) {
            return $bg;
        }

        return 'from-purple-500 to-indigo-600';
    }

    protected function eventDate(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
}
