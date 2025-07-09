<?php

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    protected $fillable = ['ip_address_hash', 'ip_address', 'region', 'city', 'isp', 'country_id'];
}
