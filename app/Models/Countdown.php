<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countdown extends Model
{
    protected $fillable = [
        'target_date',
        'graduation_year',
    ];

}
