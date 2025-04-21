<?php

namespace App\Http\Controllers\Graduation;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Countdown;

class GraduationTimeController extends Controller
{
    public function GraduationTimeIndex()
    {
        return view('Graduation.index');
    }

    public function getCountdown()
    {
        $countdown = Countdown::first();

        if (!$countdown) {
            return response()->json([
                'error' => 'Countdown data not found'
            ], 404);
        }

        // Return timestamp dalam milidetik untuk JavaScript
        return response()->json([
            'targetDate' => $countdown->target_date * 1000, // Convert to milliseconds
            'graduationYear' => $countdown->graduation_year // Tambahkan graduationYear
        ]);
    }
}
