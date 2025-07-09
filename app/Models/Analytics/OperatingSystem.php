<?php

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Model;

class OperatingSystem extends Model
{
    protected $fillable = [
        'os_name',
        'os_version',
        'os_major_version'
    ];
}
