<?php

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

class Browser extends Model
{
    protected $fillable = [
        'browser_name',
        'browser_version',
        'browser_major_version',
    ];
}
