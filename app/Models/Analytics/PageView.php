<?php

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'device_type',
        'user_id',
        'page_id',
        'referrer_id',
        'ip_address_id',
        'browser_id',
        'os_id'
    ];
}
