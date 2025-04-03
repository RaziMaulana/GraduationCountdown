<?php

namespace App\Http\Controllers\Graduation;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Countdown;

class GraduationTimeController extends Controller
{
    public function GraduationTimeIndex(){
        return view('Graduation.index');
    }

    public function getCountdown()
    {
        $countdown = Countdown::first();
        return response()->json([
            'targetDate' => $countdown ? $countdown->target_date : null
        ]);
    }

}
