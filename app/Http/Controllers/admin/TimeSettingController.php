<?php

namespace App\Http\Controllers\admin;

use App\Models\Countdown;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TimeSettingController extends Controller
{
    public function TimeSettingIndex()
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Get the countdown data to pre-fill the form
        $countdown = Countdown::first();
        return view('admin.TimeSetting', ['countdown' => $countdown]);
    }

    public function getCountdown()
    {
        $countdown = Countdown::first();

        if (!$countdown) {
            return response()->json([
                'error' => 'Countdown data not found'
            ], 404);
        }

        return response()->json([
            'targetDate' => $countdown->target_date
        ]);
    }

    public function setCountdown(Request $request)
    {
        $request->validate([
            'targetDate' => 'required|date'
        ]);

        // Get the single countdown record or create a new one if it doesn't exist
        $countdown = Countdown::firstOrNew();

        // Update the target date
        $countdown->target_date = $request->targetDate;
        $countdown->save();

        return response()->json(['success' => true]);
    }
}
