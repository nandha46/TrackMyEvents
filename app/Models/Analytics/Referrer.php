<?php

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

class Referrer extends Model
{
    protected $fillable = [
        'referrer_url',
        'referrer_domain',
        'referrer_type'
    ];
}
