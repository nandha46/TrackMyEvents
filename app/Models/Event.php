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

    public function getYears () : int {
        return (int) Carbon::parse($this->event_date)->diffInYears(null, true);
    }
    
    public function getMonths () : int {
        return (int) Carbon::parse($this->event_date)->addYear($this->getYears())->diffInMonths(null, true);
    }
    public function getDays () : int {
        return (int) Carbon::parse($this->event_date)->addYear($this->getYears())->addMonth($this->getMonths())->diffInDays(null, true);
    }
    
    public function getTime () : int {
        return (int) Carbon::parse($this->event_date)->addYear($this->getYears())->addMonth($this->getMonths())->addDays($this->getDays())->diffInHours();
    }

    protected function eventDate (): Attribute {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d-M-Y'),
        );
    }

}
