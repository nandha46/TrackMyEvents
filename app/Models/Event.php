<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'user_id'
    ];

    public function isPastEvent (): bool {
        return $this->event_date != null && Carbon::parse($this->event_date)->isPast();
    }

    public function countDown () : string {
        return Carbon::parse($this->event_date)->diffInYears()."Y ".Carbon::parse($this->event_date)->diffInMonths()."M";
    }

    public function getYears(): int {
        return (int) floor(abs(Carbon::parse($this->event_date)->diffInYears(now())));
    }
    
    public function getMonths(): int {
        $parsed = Carbon::parse($this->event_date);
        return (int) (floor(abs($parsed->diffInMonths(now()))) % 12);
    }

    public function getWeeks(): int {
        return (int) floor(abs(Carbon::parse($this->event_date)->diffInWeeks(now())));
    }

    public function getDays(): int {
        $parsed = Carbon::parse($this->event_date);
        return (int) (floor(abs($parsed->diffInDays(now()))) % 30);
    }
    
    public function getTotalDays(): int {
        return (int) floor(abs(Carbon::parse($this->event_date)->diffInDays(now())));
    }

    public function getHours(): int {
        return (int) (floor(abs(Carbon::parse($this->event_date . ' ' . ($this->event_time ?? '00:00:00'))->diffInHours(now()))) % 24);
    }

    public function getMinutes(): int {
        return (int) (floor(abs(Carbon::parse($this->event_date . ' ' . ($this->event_time ?? '00:00:00'))->diffInMinutes(now()))) % 60);
    }

    public function getSeconds(): int {
        return (int) (floor(abs(Carbon::parse($this->event_date . ' ' . ($this->event_time ?? '00:00:00'))->diffInSeconds(now()))) % 60);
    }

    public function getWorkdays(): int {
        $from = Carbon::parse($this->event_date);
        $to = now();
        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }
        return (int) floor(abs($from->diffInWeekdays($to)));
    }

    public function getGradientClasses(): string {
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

    protected function eventDate (): Attribute {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
}
